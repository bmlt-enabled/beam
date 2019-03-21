<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function comment() {
        $beamed_id_response = DB::table('posts')->select('id')->where('beamed_post_id',intval(request('parent_id')))->get();

        Post::create([
            'user_id' => request('user_id'),
            'beam_id' => request('beam_id'),
            'message'=> request('message'),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
            'parent_id' => $beamed_id_response[0]->id,
        ]);
    }
}
