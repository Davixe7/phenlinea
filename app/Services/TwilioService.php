<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $this->from = config('services.twilio.whatsapp_from');
    }

    public function sendTemplateMessage($to, $contentSid, array $variables = [], $mediaUrl = null)
    {
        $data = [
            "from"             => "whatsapp:{$this->from}",
            "contentSid"       => $contentSid,
            "contentVariables" => json_encode($variables)
        ];

        Log::info(json_encode($data));

        try {
            return $this->client->messages->create("whatsapp:$to", $data);
        } catch (Exception $e) {
            Log::error("Error Twilio enviando a $to: " . $e->getMessage());
            return false;
        }
    }
}
