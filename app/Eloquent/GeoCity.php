<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeoCity extends Model
{
    use Notifiable;

    protected $table = 'geo_cities';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'region_id',
        'country_id',
        'title',
        'title_en'
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

    public function region() {
        return $this->hasOne('App\Eloquent\GeoRegion', 'id', 'region_id');
    }
    public function country() {
        return $this->hasOne('App\Eloquent\GeoCountry', 'id', 'country_id');
    }
}
