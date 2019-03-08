<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Hello extends Controller
{
    public function index($name)
    {
        return view('hello', ['name' => $name]);
    }
}
