<?php

namespace App\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Anons extends Model
{

    //OBSERVER

    use Notifiable;
    //use Searchable;

    protected $table = 'anons';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'date',
        'will_end',
        'place',
        'organizer',
        'text',
        'reg_linc',
        'price',
        'main',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'new'
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

    /*public function toSearchableArray()
    {
        $array = $this->toArray();
        return array('id' => $array['id'], 'title' => $array['title'], 'text' => $array['text'], 'table' => 'anons', 'update_at' => Carbon::parse($array['updated_at'])->toTimeString());
    }*/

    public function getRegLincAttribute($value)
    {
        return $this->addHttp($value);
    }
    public function setRegLincAttribute($value)
    {
        $this->attributes['reg_linc'] = $this->addHttp($value);
    }
    function addHttp($url) {
        if ($url !== null && !preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }
}
