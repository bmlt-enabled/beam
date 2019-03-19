<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class XpostController extends Controller
{
    public function save(Request $request) {
        Post::create([
            'user_id' => request('user_id'),
            'beam_id' => request('beam_id'),
            'message'=> request('message'),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
            'beamed_post_id' => request('post_id'),
        ]);
    }
}
