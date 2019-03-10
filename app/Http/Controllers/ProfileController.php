<?php

namespace App\Http\Controllers;

use App\ExternalApi\BmltApi;
use App\User;
use Illuminate\Http\Request;
use GuzzleHttp;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('profile', [
            'user' => User::find($request->user()->id),
            'service_bodies' => BmltApi::getServiceBodies()
        ]);
    }

    public function save(Request $request) {
        User::where('id', $request->user()->id)
            ->update([
            'email' => request('email'),
            'info' => request('info'),
            'phone_number' => request('phone_number'),
            'service_body_id' => request('service_body_id')]);

        return redirect('home');
    }
}
