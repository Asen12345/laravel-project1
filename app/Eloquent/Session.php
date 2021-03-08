<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Session extends Model
{
    use Notifiable;

    protected $table = 'sessions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'role',
        'user_agent',
        'payload',
        'last_activity'
    ];
}
