<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TopicAnswer extends Model
{
    use Notifiable;

    protected $table = 'topic_answers';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic_id',
        'user_id',
        'published',
        'published_at',
        'text',
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

    public function topic() {
        return $this->belongsTo('App\Eloquent\Topic', 'topic_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\Eloquent\User', 'user_id', 'id');
    }

    public function socialProfileUser() {
        return $this->hasOneThrough('App\Eloquent\UserSocialProfile', 'App\Eloquent\User', 'id', 'user_id', 'user_id', 'id');
    }

}
