<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HasZhyafRoom
{

    public static function bootHasZhyafRoom()
    {
        static::created(function ($model) {
            return $model->zhyafCreate();
        });

        static::deleting(function ($model) {
            return $model->zhyafDelete();
        });
    }

    public function shouldSync()
    {
        return $this->admin->zhyaf_enabled;
    }

    public function zhyafCreate()
    {
        if (!$this->shouldSync()) { return true; }
        try {
            $zhyaf = new Devices($this->admin);
            $zhyaf->addRoom($this);
            return true;
        }
        catch (Exception $e) {
            DB::table('extensions')->delete($this->id);
            Log::error('InteractsWithZhyaf ' . $e->getMessage());
            throw $e;
        }
    }

    public function zhyafDelete()
    {
        if (!$this->shouldSync()) { return true; }
        try {
            $zhyaf = new Devices($this->admin);
            $zhyaf->deleteRoom($this);
            return true;
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
