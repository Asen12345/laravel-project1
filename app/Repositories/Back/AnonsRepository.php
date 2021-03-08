<?php

namespace App\Repositories\Back;

use App\Eloquent\Anons;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class AnonsRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Anons::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                $query->where($key, 'LIKE', '%' . $val . '%');
            }
        });

    }


}
