<?php

namespace App\Eloquent;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    /*
     * That Guard Use
     *
     * */
    protected $guard = 'admin';
    protected $table = 'admins';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'active',
        'role',
        'password',
        'open_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                       => 'increments',
        'name'                     => 'string',
        'email'                    => 'string',
        'password'                 => 'string',
        'role'                     => 'string',
        'active'                   => 'boolean',
        'remember_token'           => 'string',
        'created_at'               => 'timestamp',
        'updated_at'               => 'timestamp',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function categoryPermission()
    {
        return $this->hasMany('App\Eloquent\AdminCategoryPermission', 'user_id', 'id');
    }

    public function permissions()
    {
        return $this->hasMany('App\Eloquent\AdminHasPermission', 'user_id', 'id');
    }
}
