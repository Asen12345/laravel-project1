<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UnsubscribedUser extends Model
{
    use Notifiable;

    protected $table = 'unsubscribed_users';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'email',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
