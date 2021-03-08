<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Blog extends Model
{
    use Notifiable;

    protected $table = 'blogs';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'subject',
        'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function blogViewsCount()
    {
        return $this->posts()->sum('views_count');
    }

    public function posts()
    {
        return $this->hasMany('App\Eloquent\BlogPost', 'blog_id', 'id');
    }

    public function firstPost()
    {
        return $this->hasOne(BlogPost::class, 'blog_id', 'id');
    }

    public function lastPost()
    {
        return $this->hasOne(BlogPost::class, 'blog_id', 'id')->orderBy('created_at', 'desc');
    }

    public function votes()
    {
        return $this->hasMany('App\Eloquent\BlogPostVote','blog_id', 'id');
    }

    public function getRating(){
        $votes = $this->votes;
        $rating = 0;
        foreach ($votes as $vote) {
            $rating +=($vote->vote) ;
        }
        return $rating;
    }

    public function getCountsPostActive() {
        return $this->posts()->where('published', true)->count();
    }

    public function user()
    {
        return $this->hasOne('App\Eloquent\User', 'id', 'user_id');
    }

    public function socialProfile() {
        return $this->hasOne('App\Eloquent\UserSocialProfile', 'user_id', 'user_id');
    }

    public function comments() {
        return $this->hasManyThrough('App\Eloquent\BlogPostDiscussion', 'App\Eloquent\BlogPost', 'blog_id', 'post_id', 'id', 'id')->where('blog_posts.published', true);
    }

    public function viewPosts() {
        return $this->hasManyThrough('App\Eloquent\View', 'App\Eloquent\BlogPost', 'blog_id', 'viewable_id', 'id', 'id')->where('blog_posts.published', true);
    }

    public function subscribers() {
        return $this->hasMany('App\Eloquent\BlogPostSubscriber', 'blog_id', 'id');
    }
}
