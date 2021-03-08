<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BlogPostSubscriber extends Model
{
    use Notifiable;

    protected $table = 'blog_post_subscribers';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'blog_id',
        'user_id',
        'active',
        'email'
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

    public function socialProfileUser() {
        return $this->hasOneThrough('App\Eloquent\UserSocialProfile', 'App\Eloquent\User', 'id', 'user_id', 'user_id', 'id');
    }

    public function blog() {
        return $this->belongsTo('App\Eloquent\Blog',  'blog_id', 'id');
    }

    public function user() {
        return $this->hasOne('App\Eloquent\User',  'id', 'user_id');
    }
}
