<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ResearchesAuthor extends Model
{
    use Notifiable;

    protected $table = 'researches_authors';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'brief_text',
        'text',
        'image',
        'sort',
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

    public function researches()
    {
        return $this->hasMany('App\Eloquent\Researches', 'researches_author_id', 'id');
    }

    public function subscribers()
    {
        return $this->hasMany('App\Eloquent\ResearchAuthorSubscriber', 'author_id', 'id');
    }
}

