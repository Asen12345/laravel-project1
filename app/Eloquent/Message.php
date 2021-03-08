<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Message extends Model
{
    use Notifiable;

    protected $table = 'messages';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'thread_id',
        'user_id',
        'user_to_id',
        'body',
        'read_at',
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

    public function userSend() {
        return $this->hasOne('App\Eloquent\User', 'id', 'user_id');
    }

    public function thread() {
        return $this->hasOne('App\Eloquent\MessageThread', 'id', 'thread_id');
    }

}
