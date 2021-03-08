<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BlogPostDiscussion extends Model
{
    use Notifiable;

    protected $table = 'blog_post_discussions';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'anonym',
        'text',
        'published',
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


    public function post() {
        return $this->belongsTo('App\Eloquent\BlogPost', 'post_id', 'id');
    }

    public function user() {
       return $this->belongsTo('App\Eloquent\User', 'user_id', 'id');
    }

    public function socialProfileUser() {
        return $this->hasOneThrough('App\Eloquent\UserSocialProfile', 'App\Eloquent\User', 'id', 'user_id', 'user_id', 'id');
    }

    public function blog() {
        return $this->hasOneThrough('App\Eloquent\Blog', 'App\Eloquent\BlogPost', 'id', 'id', 'post_id', 'blog_id');
    }



}
