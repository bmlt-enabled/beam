<?php

namespace App\ExternalApi;

use GuzzleHttp;
use Illuminate\Support\Facades\Cache;

class BmltApi
{
    private static function sort_on_field(&$objects, $on, $order = 'ASC')
    {
        usort($objects, function ($a, $b) use ($on, $order) {
            return $order === 'DESC' ? -strcoll($a->{$on}, $b->{$on}) : strcoll($a->{$on}, $b->{$on});
        });
    }

    public static function getServiceBodies() {
        $service_bodies = json_decode(self::getServiceBodiesFromBmlt());
        self::sort_on_field($service_bodies, 'name');
        return $service_bodies;
    }

    public static function getServiceBodyById($id) {
        $service_bodies = json_decode(self::getServiceBodiesFromBmlt());
        foreach ($service_bodies as $service_body) {
            if ($service_body->id == $id) {
                return $service_body;
            }
        }
    }

    private static function getServiceBodiesFromBmlt() {
        if (Cache::has('service_bodies')) {
            return Cache::get('service_bodies');
        } else {
            $client = new GuzzleHttp\Client();
            $service_bodies = $client->get(config('app.bmlt_root_server') . '/client_interface/json/?switcher=GetServiceBodies')->getBody()->getContents();
            Cache::put('service_bodies', $service_bodies, 3600);
            return $service_bodies;
        }
    }
}
