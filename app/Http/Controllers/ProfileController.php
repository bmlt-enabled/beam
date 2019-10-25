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

    public function create(Request $request) {
        return view('admin.create', [
            'service_bodies' => BmltApi::getServiceBodies()
        ]);
    }

    public function admin(Request $request)
    {
        if ($request->user()->isAdmin()) {
            return view('admin.profile', [
                'user' => User::find(request('id')),
                'service_bodies' => BmltApi::getServiceBodies()
            ]);
        } else {
            return redirect('home');
        }
    }

    public function save(Request $request) {
        User::where('id', $request->user()->id)
            ->update([
            'email' => request('email'),
            'info' => request('info'),
            'phone_number' => request('phone_number'),
            'service_body_id' => request('service_body_id'),
            'notifications_flag' => array_sum(request('notifications_flag'))]);

        return redirect('home');
    }

    public function save_admin(Request $request) {
        if ($request->user()->isAdmin()) {
            User::where('id', request('id'))
                ->update([
                'email' => request('email'),
                'info' => request('info'),
                'phone_number' => request('phone_number'),
                'service_body_id' => request('service_body_id'),
                'notifications_flag' => request('notifications_flag'),
                'type' => request('is_admin') === "1" ? 'admin' : 'default']);
        }

        return redirect('home');
    }
}
