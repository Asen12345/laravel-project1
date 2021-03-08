<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserPrivacy extends Model
{
    use Notifiable;

    protected $table = 'user_privacy';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'work_phone_show',
        'mobile_phone_show',
        'skype_show',
        'web_site_show',
        'work_email_show',
        'personal_email',
        'personal_email_show',
        'about_me_show',
        'address_show'
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

}
