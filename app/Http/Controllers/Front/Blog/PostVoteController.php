<?php

namespace App\Http\Controllers\Front\Blog;

use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostVote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostVoteController extends Controller
{
    public function __construct() {

    }

    public function vote(Request $request) {
        $user_id = auth()->user()->id ?? '';
        $user_hash = md5($request->ip() . $user_id);
        $post = BlogPost::where('id', $request->post_id)->first();
        $blog_id = $post->blog()->first()->id;
        if ($request->type == 'ico-blog-like') {
            return $this->likeDislike($request, $user_hash, $post, $blog_id, '1');
        } elseif ($request->type == 'ico-blog-dislike') {
            return $this->likeDislike($request, $user_hash, $post, $blog_id, '-1');
        } else {
            return abort('404');
        }
    }

    public function likeDislike($request, $user_hash, $post, $blog_id, $vote)
    {
        $record = BlogPostVote::where('user_id', $user_hash)
            ->where('post_id', $post->id)
            ->first();

        if(empty($record)){
            BlogPostVote::create([
                'user_id' => $user_hash,
                'post_id' => $post->id,
                'blog_id' => $blog_id,
                'vote'    => $vote
            ]);
            $data = 'Спасибо за ваш голос';
        } else {
            if ($record->vote == $vote){
                $data = 'Вы уже голосовали за данную запись';
            } else {
                $record->update([
                    'vote'    => $vote
                ]);
                $data = 'Вы измненили ваш голос.';
            }
        }

        $total = $post->getRating();

        return response()->json(['total' => $total, 'success' => $data]);


    }
}
