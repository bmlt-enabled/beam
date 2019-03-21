<?php

namespace App\Http\Controllers;

use App\Beam;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $beams = Beam::all();
        return view('settings', ['beams'=>$beams]);
    }
}
