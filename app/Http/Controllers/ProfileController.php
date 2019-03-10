<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('profile', ['user' => User::find($request->user()->id)]);
    }

    public function save(Request $request) {
        User::where('id', $request->user()->id)
            ->update([
            'email' => request('email'),
            'info' => request('info'),
            'phone_number' => request('phone_number')]);

        return redirect('home');
    }
}
