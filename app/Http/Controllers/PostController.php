<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all()->sortByDesc('created_at');
        return view('posts')->with('posts', $posts);
    }

    public function save(Request $request)
    {
        Post::create([
            'user_id' => $request->user()->id,
            'message'=> request('message'),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        return redirect('posts');
    }

    public function comment(Request $request)
    {
        Post::create([
            'user_id' => $request->user()->id,
            'message'=> request('message'),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
            'parent_id' => request('parent_id')
        ]);

        return redirect('posts');
    }
}
