<?php

namespace App\Repositories\Back;

use App\Eloquent\BlogPost;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

class PostRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BlogPost::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'subject') {
                    $query->where(function ($query) use ($val) {
                        $query->whereHas('blog', function ($query) use ($val) {
                                $query->where('subject', 'LIKE', '%' . $val . '%');
                            });
                    });
                } elseif (Str::contains($key, '_id')) {
                    $query->where($key, $val);
                }  else {
                    $query->where($key, 'LIKE', '%' . $val . '%');
                }

            }
        });

    }


}
