<?php

namespace App\Repositories\Frontend\Blog;

use App\Eloquent\Tag;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class TagRepository.
 */
class TagRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Tag::class;
    }

    /**
     * @param $array array
     * @return array
     */
    public function saveTagsIfNot($array) {
        $data = array();
        foreach ($array as $tag) {
            $model = $this->model->where('id', $tag)->first();
            if (empty($model)) {
                $data[] = $this->create([
                    'name' => $tag
                ])->id;
            } else {
                $data[] = $model->id;
            }
        }
        return $data;
    }
}
