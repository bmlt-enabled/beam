<?php

namespace App\Http\Controllers;

use App\Beam;
use App\ExternalApi\BeamApi;
use App\ExternalApi\BmltApi;
use App\User;
use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all()->where('parent_id', null)->sortByDesc('created_at');
        foreach ($posts as $post) {
            if (isset($post->beam_id))
            {
                $post->user = BeamApi::GetUserForId($post->beam_id, $post->user_id);
                $post->user->service_body = BeamApi::GetServiceBodyForId($post->user->service_body_id);
            }
            else
            {
                $post->user = User::findOrFail($post->user_id);
                $post->user->service_body = BmltApi::getServiceBodyById($post->user->service_body_id);
            }
        }

        $comments = Post::all()->where('parent_id', !null)->sortByDesc('created_at');
        foreach ($comments as $comment) {
            if (isset($comment->beam_id))
            {
                $comment->user = BeamApi::GetUserForId($comment->beam_id, $comment->user_id);
                $comment->user->service_body = BeamApi::GetServiceBodyForId($comment->user->service_body_id);
            }
            else
            {
                $comment->user = User::findOrFail($comment->user_id);
                $comment->user->service_body = BmltApi::getServiceBodyById($comment->user->service_body_id);
            }
        }



        return view('posts', ['posts' => $posts, 'comments' => $comments]);
    }

    public function save(Request $request)
    {
        $response = Post::create([
            'user_id' => $request->user()->id,
            'message'=> request('message'),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        $beams = Beam::all();
        $client = new \GuzzleHttp\Client();
        $post_id = $response->id;

        foreach ($beams as $beam) {
            $response = $client->request('POST', $beam->url . '/api/xposts/' . $beam->id . '/save', [
                'form_params' => [
                    'user_id' => $request->user()->id,
                    'message' => request('message'),
                    'beam_id' => $beam->id,
                    'post_id' => $post_id,
                 ]
            ]);
        }

        return redirect('posts');
    }

    public function comment(Request $request)
    {
        $response = Post::create([
            'user_id' => $request->user()->id,
            'message'=> request('message'),
            'parent_id' => intval(request('parent_id')),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        $parent_id_response = Post::query()->where(['id'=>intval(request('parent_id'))]);

        $beams = Beam::all();
        $client = new \GuzzleHttp\Client();

        foreach ($beams as $beam) {
            $response = $client->request('POST', $beam->url . '/api/xposts/' . $beam->id . '/comment/save', [
                'form_params' => [
                    'user_id' => $request->user()->id,
                    'message' => request('message'),
                    'beam_id' => $beam->id,
                    'parent_id' => $parent_id_response->
                ]
            ]);
        }

        return redirect('posts');
    }
}
