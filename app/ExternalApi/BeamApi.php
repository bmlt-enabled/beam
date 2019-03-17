<?php
/**
 * Created by IntelliJ IDEA.
 * User: danny
 * Date: 2019-03-17
 * Time: 12:33
 */

namespace App\ExternalApi;


use App\Beam;
use Illuminate\Support\Facades\Cache;

class BeamApi
{
    private static function getUsersResponseFromBeams() {
        if (Cache::has('beamed_users'))
        {
            return Cache::get('beamed_users');
        }
        else
        {
            $beams = Beam::all();
            $client = new \GuzzleHttp\Client();
            $beam_responses = [];

            foreach ($beams as $beam) {
                $beams_responses_with_ids = [
                    'beam_responses' => $client->get($beam->url . '/api/users/list')->getBody()->getContents(),
                    'beam' => $beam
                ];
                array_push($beam_responses, $beams_responses_with_ids);
            }

            Cache::put('beamed_users', $beam_responses, 3600);
            return $beam_responses;
        }
    }

    public static function GetUsers()
    {
        $beamed_users = [];
        foreach (self::getUsersResponseFromBeams() as $beam_response) {
            $deserialized_beam_response = json_decode($beam_response['beam_responses']);
            foreach ($deserialized_beam_response->users as $beam_response_item) {
                $beam_response_item->beam = $beam_response['beam'];
            }
            array_push($beamed_users, $deserialized_beam_response);
        }

        return $beamed_users;
    }


}
