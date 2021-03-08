<?php

namespace App\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Topic extends Model
{
    use Notifiable;
    //use Searchable;

    protected $table = 'topics';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'published',
        'url_ru',
        'url_en',
        'text',
        'published_at',
        'main_topic',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'new',
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

    public function shouldBeSearchable()
    {
        if ($this->published == true) {
            return true;
        } else {
            return false;
        }
    }
    /*public function toSearchableArray()
    {
        $array = $this->toArray();
        return array('id' => $array['id'], 'title' => $array['title'], 'text' => $array['text'], 'table' => 'topics', 'update_at' => Carbon::parse($array['updated_at'])->toTimeString());
    }*/


    public function subscriber() {
        return $this->hasManyThrough('App\Eloquent\User', 'App\Eloquent\TopicSubscriber', 'topic_id', 'id', 'id', 'user_id');
    }
    public function scene() {
        return $this->hasManyThrough('App\Eloquent\NewsScene', 'App\Eloquent\NewsIdNewsSceneId', 'news_id', 'id', 'id', 'news_scene_id');
    }

    public function recordsSubscribers() {
        return $this->hasMany('App\Eloquent\TopicSubscriber', 'topic_id', 'id');
    }

    public function answers() {
        return $this->hasMany('App\Eloquent\TopicAnswer', 'topic_id', 'id');
    }
}
