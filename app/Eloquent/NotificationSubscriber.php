<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NotificationSubscriber extends Model
{
    use Notifiable;

    protected $table = 'notification_subscribers';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
