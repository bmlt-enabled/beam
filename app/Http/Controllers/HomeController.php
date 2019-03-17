<?php

namespace App\Http\Controllers;

use App\ExternalApi\BeamApi;
use App\ExternalApi\BmltApi;
use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->service_body = isset($user->service_body_id) && $user->service_body_id > 0
                ? BmltApi::getServiceBodyById($user->service_body_id) : "";
        }

        $beamed_users = BeamApi::GetUsers();
        $beamed_users_with_service_bodes = [];

        foreach ($beamed_users[0]->users as $beamed_user) {
            $beamed_user->service_body = $this->getBeamedServiceBodyForId($beamed_users[0]->service_bodies, $beamed_user->service_body_id);
            array_push($beamed_users_with_service_bodes, $beamed_user);
        }

        return view('home', ['users' => $users, 'beamed_users' => $beamed_users_with_service_bodes]);
    }

    private function getBeamedServiceBodyForId($service_bodies, $id) {
        foreach ($service_bodies as $service_body) {
            if ($service_body->id == $id) {
                return $service_body;
            }
        }
    }
}
