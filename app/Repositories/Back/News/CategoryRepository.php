<?php

namespace App\Repositories\Back\News;

use App\Eloquent\Company;
use App\Eloquent\NewsCategory;
use App\Eloquent\User;
use App\Eloquent\UserSocialProfile;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

/**
 * Class UserRepository.
 */
class CategoryRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return NewsCategory::class;
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
                }else {
                    if ($key === 'name') {
                        $query->where('name', 'LIKE', '%' . $val . '%')
                            ->orWhere('email', 'LIKE', '%' . $val . '%')
                            ->orWhere('company', 'LIKE', '%' . $val . '%');
                    } else {
                        $query->where($key, 'LIKE', '%' . $val . '%');
                    }
                }
            }
        });

    }

}
