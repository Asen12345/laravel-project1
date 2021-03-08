<?php

namespace App\Eloquent;

use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Banner extends Model implements ViewableContract
{
    use Notifiable;
    use Viewable;
    use ViewCountable;

    /*USE OBSERVER FOR DATE*/

    protected $table = 'banners';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'published',
        'banner_place_id',
        'blog_announce_id',
        'date_from',
        'date_to',
        'image',
        'link',
        'click',
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

    public function bannerPlace()
    {
        return $this->hasOne('App\Eloquent\BannerPlace', 'id', 'banner_place_id');
    }

//    public function viewBanner() {
//        return $this->morphMany('App\Eloquent\View', 'viewable');
//    }

}
