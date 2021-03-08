<?php

namespace App\Repositories\Frontend\News;

use App\Eloquent\News;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class NewsRepository.
 */
class NewsRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return News::class;
    }
}
