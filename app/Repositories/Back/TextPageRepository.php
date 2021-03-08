<?php

namespace App\Repositories\Back;

use App\Eloquent\News;
use App\Eloquent\NewsIdNewsSceneId;
use App\Eloquent\TextPage;
use App\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Repositories\Back\UserRepository;
use Carbon\Carbon;
use DB;
use Exception;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;


/**
 * Class NewsRepository.
 */
class TextPageRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return TextPage::class;
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
