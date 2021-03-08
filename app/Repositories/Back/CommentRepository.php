<?php

namespace App\Repositories\Back;

use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostDiscussion;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

class CommentRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BlogPostDiscussion::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'title') {
                    $query->where(function ($query) use ($val) {
                        $query->whereHas('post', function ($query) use ($val) {
                                $query->where('title', 'LIKE', '%' . $val . '%');
                            });
                    });
                } elseif ($key === 'user'){
                    $query->where(function ($query) use ($val) {
                        $query->whereHas('user', function ($query) use ($val) {
                            $query->where('name', 'LIKE', '%' . $val . '%');
                        });
                    });
                } elseif (Str::contains($key, '_id')) {
                    $query->where($key, $val);
                } else {
                    $query->where($key, 'LIKE', '%' . $val . '%');
                }

            }
        });

    }


}
