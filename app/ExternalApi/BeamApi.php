<?php
/**
 * Created by IntelliJ IDEA.
 * User: danny
 * Date: 2019-03-17
 * Time: 12:33
 */

namespace App\ExternalApi;


use App\Beam;

class BeamApi
{
    public static function GetUsers() {
        $beams = Beam::all();
        $client = new \GuzzleHttp\Client();

        foreach ($beams as $beam) {
            $response = $client->get($beam->url . '/api/users/list');
        }
    }
}
