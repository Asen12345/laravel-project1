<?php

namespace App\Repositories\Back\Topic;

use App\Eloquent\TopicAnswer;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

class AnswerRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return TopicAnswer::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'title') {
                    $query->where(function ($query) use ($val) {
                        $query->whereHas('topic', function ($query) use ($val) {
                            $query->where('title', 'LIKE', '%' . $val . '%');
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
