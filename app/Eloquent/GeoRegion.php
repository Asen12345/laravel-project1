<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeoRegion extends Model
{
    use Notifiable;

    protected $table = 'geo_regions';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
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

    /*public function getRecord() {
        return $this->hasMany('App\Eloquent\MortgageRate', 'bank_id', 'id');
    }*/
}
