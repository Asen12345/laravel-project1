<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TopicSubscriber extends Model
{
    use Notifiable;

    protected $table = 'topic_subscribers';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic_id',
        'user_id'
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

    public function topic(){
        return $this->hasOne('App\Eloquent\Topic', 'id', 'topic_id');
    }
    public function user(){
        return $this->hasOne('App\Eloquent\User', 'id', 'user_id');
    }
}
