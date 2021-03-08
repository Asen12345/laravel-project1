<?php

namespace App\Eloquent;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MailTemplate extends Model
{
    use Notifiable;
    /*
     * That Guard Use
     *
     * */
    protected $guard = 'admin';
    protected $table = 'mail_template';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'name',
        'subject',
        'text',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'increments',
        'template_id'   => 'string',
        'name'          => 'string',
        'subject'       => 'string',
        'text'          => 'text',
        'created_at'    => 'timestamp',
        'updated_at'    => 'timestamp',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
