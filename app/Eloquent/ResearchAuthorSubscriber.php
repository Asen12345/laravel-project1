<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ResearchAuthorSubscriber extends Model
{
    use Notifiable;

    protected $table = 'research_author_subscribers';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'author_id',
        'user_id',
        'active',
        'email'
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

    public function author() {
        return $this->belongsTo('App\Eloquent\ResearchAuthor',  'author_id', 'id');
    }

    public function user() {
        return $this->hasOne('App\Eloquent\User',  'id', 'user_id');
    }

}
