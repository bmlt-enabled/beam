<?php

namespace App\Http\Controllers;

use App\Beam;
use App\ExternalApi\BeamApi;
use App\ExternalApi\BmltApi;
use App\Notifications\PostCreatedEmail;
use App\NotificationTypes;
use App\User;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

            $post->message = $this->makeLinks(str_replace("\n", "<br/>", $post->message));
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

            $comment->message = $this->makeLinks(str_replace("\n", "<br/>", $comment->message));
        }



        return view('posts', ['posts' => $posts, 'comments' => $comments]);
    }

    public function save(Request $request)
    {
        $message = strip_tags(request('message'));
        $response = Post::create([
            'user_id' => $request->user()->id,
            'message'=> $message,
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        $users = User::whereRaw(sprintf('notifications_flag & %s = 1', NotificationTypes::$EMAIL))->get();
        if (isset($users)) {
            Notification::send($users, new PostCreatedEmail(
                sprintf('%s: %s', $request->user()->name, $message),
                sprintf('/posts#%s', $response->id),
                sprintf('%s', substr($message, 0, 50))));
        }

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
        $message = strip_tags(request('message'));
        $response = Post::create([
            'user_id' => $request->user()->id,
            'message'=> $message,
            'parent_id' => intval(request('parent_id')),
            'created_at' => gmdate("Y-m-d\TH:i:s\Z"),
        ]);

        $users = User::whereRaw(sprintf('notifications_flag & %s = 1', NotificationTypes::$EMAIL))->get();
        if (isset($users)) {
            $post = Post::query()->find(intval(request('parent_id')));
            Notification::send($users, new PostCreatedEmail(
                sprintf('%s: %s', $request->user()->name, $message),
                sprintf('/posts#%s', intval(request('parent_id'))),
                sprintf('%s', substr($post['message'], 0, 50))));
        }

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

    private function makeLinks($str) {
        $reg_exUrl = "/(((http)+(s){0,1}|(ftp)(s){0,1}|mms)[:]{0,1}[\/]*){0,1}[a-z0-9\-]+[\.][a-z0-9\-]+[\.]{0,1}[a-z]{0,3}[\.]{0,1}[a-z]{0,2}([\/\\\\\\\\]+[a-z0-9%\-\_\.\?\&\=\|]*)*/i";
        $urls = array();
        $urlsToReplace = array();
        if (preg_match_all($reg_exUrl, $str, $urls)) {
            $numOfMatches = count($urls[0]);
            $numOfUrlsToReplace = 0;
            for ($i = 0; $i < $numOfMatches; $i++) {
                $alreadyAdded = false;
                $numOfUrlsToReplace = count($urlsToReplace);
                for ($j = 0; $j < $numOfUrlsToReplace; $j++) {
                    if($urlsToReplace[$j] == $urls[0][$i]) {
                        $alreadyAdded = true;
                    }
                }

                if (!$alreadyAdded) {
                    array_push($urlsToReplace, $urls[0][$i]);
                }
            }

            $numOfUrlsToReplace = count($urlsToReplace);

            for ($i = 0; $i < $numOfUrlsToReplace; $i++) {
                $str = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\" target=\"_blank\">".$urlsToReplace[$i]."</a>", $str);
            }

            return $str;
        } else {
            return $str;
        }
    }
}
