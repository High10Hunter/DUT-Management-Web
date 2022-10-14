<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    public static function getAndCache()
    {
        $configs = cache()->remember(
            'attendanceConfigs',
            86400,
            function () {
                return self::query()
                    ->get();
            }
        );

        $data = $configs;
        $arr = [];
        foreach ($data as $each) {
            $arr[$each->key] = $each->value;
        }
        $configs = $arr;

        return $configs;
    }
}
