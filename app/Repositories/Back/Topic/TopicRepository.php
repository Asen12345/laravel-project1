<?php

namespace App\Repositories\Back\Topic;

use App\Eloquent\Topic;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class TopicRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Topic::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                    $query->where($key, 'LIKE', '%' . $val . '%');
                }
        });

    }


}
