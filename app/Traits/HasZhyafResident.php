<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait HasZhyafResident
{

    public static function bootInteractsWithZhyaf()
    {

        static::updating(function ($model) {
            $path = request()->file('picture') ? request()->file('picture')->getPathName() : null;
            return $model->zhyafUpdate($path);
        });

        static::deleting(function ($model) {
            return $model->zhyafDelete();
        });
    }

    public function shouldSync()
    {
        return $this->admin->zhyaf_enabled;
    }

    public function zhyafCreate($picturePath = null)
    {
        if (!$this->shouldSync()) { return true; }
        try {
            $zhyaf = new Devices($this->admin);
            $zhyaf->addResident($this, $picturePath);
            return true;
        }
        catch (Exception $e) {
            Log::error('InteractsWithZhyaf ' . $e->getMessage());
            throw $e;
        }
    }

    public function zhyafUpdate($picturePath = null)
    {
        if (!$this->shouldSync()) { return true; }
        try {
            $zhyaf = new Devices($this->admin);
            $zhyaf->addResident($this, $picturePath);
            return true;
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function zhyafDelete()
    {
        if (!$this->shouldSync()) { return true; }
        try {
            $zhyaf = new Devices($this->admin);
            $zhyaf->deleteResident($this);
            return true;
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function grantAccessToAllDoors()
    {
        if (!$this->shouldSync()) { return true; }
        try {
            $zhyaf  = new Devices($this->admin);
            $devSns = $zhyaf->getUnitDevices($this->extension->admin_id)->pluck('devSn')->toArray();
            $devSns = implode(",", $devSns);
            $zhyaf->addDeviceAuth($this, $devSns);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
