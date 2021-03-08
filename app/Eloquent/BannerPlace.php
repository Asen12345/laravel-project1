<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BannerPlace extends Model
{
    use Notifiable;

    protected $table = 'banner_places';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'place',
        'view_count',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'view_count' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
