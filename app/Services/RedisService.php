<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    const RDS_VACCINE_DATE_DB = 1;

    public function __construct()
    {
    }

    public function addVaccinationDate($nid, $keyType, $data)
    {
        $redis = Redis::connection();
        $redis->select(self::RDS_VACCINE_DATE_DB);
        $key = $nid;
        $redis->hmset($key, [
            $keyType => json_encode($data),
        ]);

        return true;
    }

    public function getVaccinationDate($key, $keyType)
    {
        $redis = Redis::connection();
        $redis->select(self::RDS_VACCINE_DATE_DB);

        return json_decode($redis->hget($key, $keyType));
    }
}
