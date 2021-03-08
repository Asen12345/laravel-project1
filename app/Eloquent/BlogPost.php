<?php

namespace App\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use Laravel\Scout\Searchable;

class BlogPost extends Model implements ViewableContract
{
    use Notifiable;
    use Viewable;
    //use Searchable;
    use ViewCountable;

    protected $table = 'blog_posts';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'blog_id',
        'published',
        'announce',
        'title',
        'text',
        'published_at',
        'new',
        'to_newsletter'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'published' => 'boolean'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return array('id' => $array['id'], 'title' => $array['title'], 'text' => $array['text'], 'table' => 'blog_posts', 'update_at' => Carbon::parse($array['updated_at'])->toTimeString());
    }

    public function shouldBeSearchable()
    {
        if ($this->published == true) {
            return true;
        } else {
            return false;
        }
    }

    public function blog()
    {
        return $this->belongsTo('App\Eloquent\Blog', 'blog_id', 'id');
    }

    public function tags() {
        return $this->hasmanyThrough('App\Eloquent\Tag', 'App\Eloquent\BlogPostTags', 'blog_post_id', 'id', 'id', 'tag_id');
    }

    public function tagsRecords() {
        return $this->hasMany('App\Eloquent\BlogPostTags','blog_post_id', 'id');
    }

    public function user() {
        return $this->hasOneThrough('App\Eloquent\User', 'App\Eloquent\Blog', 'id', 'id', 'blog_id', 'user_id');
    }

    public function votes()
    {
        return $this->hasMany('App\Eloquent\BlogPostVote','post_id', 'id');
    }

    public function getRating(){
        $votes = $this->votes;
        $rating = 0;
        foreach ($votes as $vote) {
            $rating +=($vote->vote) ;
        }
        return $rating;
    }

    public function comments() {
        return $this->hasMany('App\Eloquent\BlogPostDiscussion', 'post_id', 'id');
    }

    public function subscribers() {
        return $this->hasOneThrough('App\Eloquent\BlogPostSubscriber', 'App\Eloquent\Blog', 'id', 'blog_id', 'blog_id', 'id');
    }

}
