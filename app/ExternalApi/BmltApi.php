<?php

namespace App\ExternalApi;

use GuzzleHttp;

class BmltApi
{
    public static function getServiceBodies() {
        return json_decode(self::getServiceBodiesFromBmlt());
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
        if (isset($_SESSION['service_bodies'])) {
            return $_SESSION['service_bodies'];
        } else {
            $client = new GuzzleHttp\Client();
            $service_bodies = $client->get("https://bmlt.sezf.org/main_server/client_interface/json/?switcher=GetServiceBodies")->getBody();
            $_SESSION['service_bodies'] = json_encode($service_bodies);
            return $service_bodies;
        }
    }
}
