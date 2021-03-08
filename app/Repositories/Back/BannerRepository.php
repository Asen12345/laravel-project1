<?php

namespace App\Repositories\Back;

use App\Eloquent\Banner;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;


/**
 * Class NewsRepository.
 */
class BannerRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Banner::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'page') {
                    continue;
                }
                if (is_null($val)) {
                    $query->where($key, 'LIKE', '%' .''. '%');
                } elseif (Str::contains($key, '_id')) {
                    $query->where($key, $val);
                } else {
                    if ($key === 'name') {
                        $query->where('name', 'LIKE', '%' . $val . '%');
                    } else {
                        $query->where($key, 'LIKE', '%' . $val . '%');
                    }
                }
            }
        });

    }
}
