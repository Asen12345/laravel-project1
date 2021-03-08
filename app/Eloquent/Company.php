<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;

    protected $table = 'companies';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'name_en',
        'type_id'
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

    public function typeCompany() {
        return $this->hasOne('App\Eloquent\CompanyType', 'id', 'type_id');
    }

    public function users() {
        return $this->hasManyThrough('App\Eloquent\User', 'App\Eloquent\UserSocialProfile', 'company_id', 'id', 'id', 'user_id');
    }

    public function userCompany() {
        return $this->hasOne('App\Eloquent\User', 'name', 'name')->where('permission', 'company');
    }

}
