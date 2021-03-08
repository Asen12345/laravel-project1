<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MailingUser extends Model
{
    use Notifiable;

    protected $table = 'mailing_users';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'mailing_id',
        'user_id',
        'read_at'
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

    public function mail()
    {
        return $this->hasOne('App\Eloquent\Mailing', 'id', 'mailing_id');
    }
    public function user()
    {
        return $this->hasOne('App\Eloquent\User', 'id', 'user_id');
    }
}
