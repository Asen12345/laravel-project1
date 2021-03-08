<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MetaTags extends Model
{
    use Notifiable;
    /*
     * That Guard Use
     *
     * */
    protected $guard = 'admin';
    protected $table = 'meta_tags';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'meta_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'               => 'increments',
        'name'             => 'string',
        'meta_id'          => 'string',
        'meta_title'       => 'string',
        'meta_keywords'    => 'string',
        'meta_description' => 'text',
        'created_at'       => 'timestamp',
        'updated_at'       => 'timestamp',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
