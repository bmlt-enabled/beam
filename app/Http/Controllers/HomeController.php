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
        $users = self::all();
        foreach ($users as $user) {
            $user->service_body = isset($user->service_body_id) && $user->service_body_id > 0
                ? BmltApi::getServiceBodyById($user->service_body_id) : "";
        }

        $beamed_users_with_service_bodes = User::getBeamedUsers();

        return view('home', ['users' => $users, 'beamed_users' => $beamed_users_with_service_bodes]);
    }
}
