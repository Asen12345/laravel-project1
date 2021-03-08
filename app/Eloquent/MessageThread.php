<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MessageThread extends Model
{
    use Notifiable;

    protected $table = 'message_threads';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'subject',
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

    public function messages()
    {
        return $this->hasMany('App\Eloquent\Message', 'thread_id', 'id');
    }

    public function messageParticipants()
    {
        return $this->hasMany('App\Eloquent\MessageParticipant', 'thread_id', 'id');
    }

    public function getLastMessage()
    {
        return $this->messages()->orderBy('id', 'desc')->first();
    }

    public function lastMessage()
    {
        return $this->hasOne('App\Eloquent\Message', 'thread_id', 'id')->latest();
    }

    public function notReadCount($exclude) {
        return $this->messages()->whereNull('read_at')->where('user_id', '!=', $exclude)->count();
    }

}
