<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Eloquent\Anons;
use App\Eloquent\BlogPost;
use App\Eloquent\News;
use App\Eloquent\Researches;
use App\Eloquent\Topic;
use Laravel\Scout\Searchable;

class MultiSearch extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        News::class,
        BlogPost::class,
        Researches::class,
        Anons::class,
        Topic::class,
        //User::class,
    ];

    public function shouldBeSearchable()
    {
        // Check if the class uses the Searchable trait before calling shouldBeSearchable
        if (array_key_exists(Searchable::class, class_uses($this->model))) {
            return $this->model->shouldBeSearchable();
        }
    }
}
