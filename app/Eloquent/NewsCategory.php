<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NewsCategory extends Model
{
    use Notifiable;

    protected $table = 'news_categories';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'url_ru',
        'url_en',
        'sort',
        'parent_id',
        'meta_title',
        'meta_keywords',
        'meta_description'
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

    public function news()
    {
        return $this->hasMany('App\Eloquent\News', 'news_category_id', 'id');
    }

    public function newsWithChildCategory () {

    }

    public function parent()
    {
        return $this->belongsTo('App\Eloquent\NewsCategory', 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany('App\Eloquent\NewsCategory', 'parent_id', 'id');
    }

    public function getParentNames() {
        if($this->parent) {
            return $this->parent->getParentNames(). " > " . $this->name;
        } else {
            return $this->name;
        }
    }
}
