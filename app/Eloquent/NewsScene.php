<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NewsScene extends Model
{
    use Notifiable;

    protected $table = 'news_scenes';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'image',
        'news_scene_group_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
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

    public function getGroup() {
        return $this->belongsTo('App\Eloquent\NewsSceneGroup', 'news_scene_group_id', 'id');
    }

    public function getNews() {
        return $this->hasManyThrough('App\Eloquent\News', 'App\Eloquent\NewsIdNewsSceneId', 'news_scene_id', 'id', 'id', 'news_id');
    }
}
