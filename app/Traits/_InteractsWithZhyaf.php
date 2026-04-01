<?php

namespace App\Traits\Zhyaf;

use App\Admin;
use App\Porteria;
use App\Extension;
use App\Resident;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait InteractsWithZhyaf
{
    protected $zhyafAppId;
    protected $zhyafAppSecret;
    protected $zhyafApiVersion;
    protected $zhyafCacheKey;

    protected function multipartQuery($query){
        $multipart = [];
        foreach ($query as $key => $value) {
            $multipart[] = ['name' => $key, 'contents' => $value];
        }
        return $multipart;
    }

    protected function getDeviceApiVersion()
    {
        if ($this instanceof Admin) {
            return $this->device_api_version;
        }
        if ($this instanceof Porteria) {
            return $this->admin->device_api_version;
        }
        if ($this instanceof Extension) {
            return $this->admin->device_api_version;
        }
        if ($this instanceof Resident) {
            return $this->extension->admin->device_api_version;
        }
    }

    protected function getExtCommunityUuid()
    {
        if ($this instanceof Admin) {
            return $this->id;
        }
        if ($this instanceof Porteria) {
            return $this->admin_id;
        }
        if ($this instanceof Extension) {
            return $this->admin_id;
        }
        if ($this instanceof Resident) {
            return $this->extension->admin_id;
        }
    }

    protected function getAccessToken()
    {
        $this->zhyafApiVersion = $this->getApiVersion();
        $this->zhyafAppId      = config("zhyaf.$this->zhyafApiVersion.app_secret");
        $this->zhyafAppSecret  = config("zhyaf.$this->zhyafApiVersion.app_id");
        $this->zhyafBaseUri    = config("zhyaf.$this->zhyafApiVersion.base_url");
        $this->zhyafCacheKey   = "zhyaf_access_token_$this->zhyafApiVersion";
        $this->api             = new Client(['base_uri' => $this->zhyafBaseUri, 'headers'  => ['language' => 'en_ES', 'timeZone' => 'America/Bogota']]);

        return Cache::remember($this->zhyafCacheKey, 7200, function () {
            try {
                $response = $this->api->get('platCompany/extapi/getAccessToken', [
                    'multipart' => [
                        ['name' => 'timeZone',    'contents'  => 'America/Bogota'],
                        ['name' => 'language',    'contents'  => 'es_ES'],
                        ['name' => 'appId',       'contents'  => $this->zhyafAppId],
                        ['name' => 'appSecret',   'contents'  => $this->zhyafAppSecret]
                    ]
                ]);

                $body = json_decode($response->getBody());

                $data = property_exists($body, 'data') ? $body->data : null;
                $accessToken = ($data && property_exists($data, 'accessToken')) ? $data->accessToken : null;
                return $accessToken;
            } catch (GuzzleException $e) {
                Storage::append('zhyaf.error.log', $e->getMessage());
                return null;
            }
        });
    }

    protected function fetchZhyaf($endpoint, $query)
    {
        $baseQuery = [
            'accessToken'      => $this->getAccessToken(),
            'extCommunityUuid' => $this->getExtCommunityId()
        ];

        $multipart = $this->multipartQuery( array_merge($baseQuery, $query) );

        try {
            $response = $this->api->post($endpoint, compact('multipart'));
            $body     = json_decode($response->getBody());
            $code     = property_exists($body, 'code') ? $body->code : null;

            if (is_null($code)) {
                throw new Exception("Zhyaf request failed without error code", 522);
            }

            if ($code != 0) {
                throw new Exception($body->msg . " " . $this->name, $code);
            }

            return property_exists($body, 'data') ? $body->data : $body->msg;
        } catch (Exception $e) {
            Storage::append('zhyaf.error.log', $endpoint . " " . $e->getCode() . " " . $e->getMessage());
            throw $e;
        }
    }
}
