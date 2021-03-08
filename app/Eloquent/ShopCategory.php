<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShopCategory extends Model
{
    use Notifiable;

    protected $table = 'shop_categories';
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
        'show',
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

    public function parent()
    {
        return $this->belongsTo('App\Eloquent\ShopCategory', 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany('App\Eloquent\ShopCategory', 'parent_id', 'id');
    }

    public function getParentNames() {
        if($this->parent) {
            return $this->parent->getParentNames(). " > " . $this->name;
        } else {
            return $this->name;
        }
    }

    public function researches()
    {
        return $this->belongsToMany('App\Eloquent\Researches', 'shop_category_researches', 'shop_category_id', 'researches_id');
    }

}
