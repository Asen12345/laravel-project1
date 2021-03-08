<?php

namespace App\Eloquent;

use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class News extends Model implements ViewableContract
{
    use Notifiable;
    use Viewable;
    //use Searchable;
    use ViewCountable;

    protected $table = 'news';
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
        'title',
        'url_ru',
        'url_en',
        'announce',
        'text',
        'news_category_id',
        'source_name',
        'source_url',
        'vip',
        'author_show',
        'author_text_val',
        'author_user_id',
        'yandex',
        'new',
        'posted',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'news_category_id' => 'integer'
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
    ];


   /* public function toSearchableArray()
    {
        $array = $this->toArray();
        return array('id' => $array['id'], 'title' => $array['title'], 'text' => $array['text'], 'table' => 'news', 'update_at' => Carbon::parse($array['updated_at'])->toTimeString());
    }*/
    public function shouldBeSearchable()
    {
        if ($this->published == true) {
            return true;
        } else {
            return false;
        }
    }


    public function category() {
        return $this->belongsTo('App\Eloquent\NewsCategory', 'news_category_id', 'id');
    }

    public function scene() {
        return $this->hasManyThrough('App\Eloquent\NewsScene', 'App\Eloquent\NewsIdNewsSceneId', 'news_id', 'id', 'id', 'news_scene_id');
    }

    public function user() {
        return $this->hasOne('App\Eloquent\User', 'id', 'author_user_id');
    }

	//похожие новости
    public function similarNews($max = 3, $excite_id = null) {
        if ($this->scene) {
            $similar = collect();
            foreach ($this->scene as $scene) {
                $news = $scene->getNews()
                    //->whereMonth('published_at', $month)
					->whereBetween('published_at', [Carbon::now()->subMonths(1), Carbon::now()])
                    ->where('published', 1)
                    ->get();

                $news = ($max > $news->count())? $news->all() : $news->random($max);

                foreach ($news as $row) {
                    if (!$similar->contains('id', $row->id) && ($row->id !== $excite_id)) {
                        $similar->push($row);
                    }
                }
            }
            if ($similar->count() < $max) {
                return $similar->all();
            } else {
                return $similar->random($max);
            }
        } else {
            return null;
        }
    }

    /*accessor*/
    public function getSourceUrlAttribute($value)
    {
        return $this->addHttp($value);
    }
    /*Mutator*/
    public function setSourceUrlAttribute($value)
    {
        $this->attributes['source_url'] = $this->addHttp($value);
    }
    function addHttp($url) {
        if ($url !== null && !preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }


}
