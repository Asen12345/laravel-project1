<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Mailing extends Model
{
    use Notifiable;

    protected $table = 'mailings';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'subject',
        'text',
        'all_user',
        'file_patch'
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

    public function mailingUsers()
    {
        return $this->hasMany('App\Eloquent\MailingUser', 'mailing_id', 'id');
    }
}
