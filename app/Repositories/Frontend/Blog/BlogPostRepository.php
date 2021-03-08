<?php

namespace App\Repositories\Frontend\Blog;

use App\Eloquent\BlogPost;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


/**
 * Class BlogRepository.
 */
class BlogPostRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BlogPost::class;
    }
}
