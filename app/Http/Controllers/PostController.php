<?php

namespace App\Http\Controllers;

use App\Beam;
use App\ExternalApi\BeamApi;
use App\ExternalApi\BmltApi;
use App\Notifications;
use App\Notifications\PostCreated;
use App\User;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
        $message = request('message');
        $response = Post::create([
            'user_id' => $request->user()->id,
            'message'=> $message,
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        // TODO: get only users who want to get email notifications
        $users = User::all();
        Notification::send($users, new PostCreated($message,
            sprintf('/posts#%s', $response->id),
            sprintf('New Post: %s', substr($message, 0, 50))));

        $beams = Beam::all();
        $client = new \GuzzleHttp\Client();
        $post_id = $response->id;
#
        foreach ($beams as $beam) {
            $response = $client->request('POST', $beam->url . '/api/xposts/' . $beam->id . '/save', [
                'form_params' => [
                    'user_id' => $request->user()->id,
                    'message' => $message,
                    'beam_id' => $beam->id,
                    'post_id' => $post_id,
                 ]
            ]);
        }

        return redirect('posts');
    }

    public function comment(Request $request)
    {
        $message = request('message');
        $response = Post::create([
            'user_id' => $request->user()->id,
            'message'=> $message,
            'parent_id' => intval(request('parent_id')),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        // TODO: get only users who want to get email notifications
        $users = User::all();
        $post = Post::query()->find(intval(request('parent_id')));
        Notification::send($users, new PostCreated($message,
            sprintf('/posts#%s', $response->id),
            sprintf("New Comment: %s", substr($post['message'], 0, 50))));

        $parent_id_response = DB::table('posts')->select('beamed_post_id')->where('id',intval(request('parent_id')))->get();
        if (isset($parent_id_response[0]->beamed_post_id))
        {
            $parent_id = $parent_id_response[0]->beamed_post_id;
        }
        else
        {
            $parent_id = intval(request('parent_id'));
        }

        $beams = Beam::all();
        $client = new \GuzzleHttp\Client();

        foreach ($beams as $beam) {
            $response = $client->request('POST', $beam->url . '/api/xposts/' . $beam->id . '/comment/save/' . $parent_id, [
                'form_params' => [
                    'user_id' => $request->user()->id,
                    'message' => $message,
                ]
            ]);
        }

        return redirect('posts');
    }
}
