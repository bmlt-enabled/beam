<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class MigrationController extends Controller
{
    public function index()
    {
        Artisan::call('migrate', ['--path' => "database/migrations", '--force' => true]);
    }
}
