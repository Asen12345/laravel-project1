<?php

namespace App\Eloquent;

use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Researches extends Model implements ViewableContract
{
    use Notifiable;
    use Viewable;
    //use Searchable;
    use ViewCountable;

    protected $table = 'researches';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'shop_category_id',
        'annotation',
        'content',
        'table',
        'image',
        'demo_file',
        'file',
        'published_at',
        'page',
        'format',
        'language',
        'price',
        'researches_author_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'download',
        'main',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /*public function toSearchableArray()
    {
        $array = $this->toArray();
        return array('id' => $array['id'], 'title' => $array['title'], 'text' => $array['content'], 'table' => 'researches', 'update_at' => Carbon::parse($array['updated_at'])->toTimeString());
    }*/

    public function author()
    {
        return $this->hasOne('App\Eloquent\ResearchesAuthor', 'id', 'researches_author_id');
    }

    public function viewsCount() {
        return $this->morphMany('App\Eloquent\View', 'viewable', 'viewable_type', 'viewable_id', 'id');
    }

    public function category() {
        return $this->belongsToMany('App\Eloquent\ShopCategory', 'shop_category_researches', 'researches_id', 'shop_category_id');
    }

}
