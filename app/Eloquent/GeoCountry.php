<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeoCountry extends Model
{
    use Notifiable;

    protected $table = 'geo_countries';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'title_en',
        'position',
        'hidden'
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

    public function regions() {
        return $this->hasMany('App\Eloquent\GeoRegion', 'country_id', 'id');
    }

    public function city() {
        return $this->belongsToMany('App\Eloquent\GeoCity', 'App\Eloquent\GeoCountry', 'id', 'id', 'id', 'region_id');
    }

}
