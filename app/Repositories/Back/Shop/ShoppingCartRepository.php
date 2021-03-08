<?php

namespace App\Repositories\Back\Shop;

use App\Eloquent\News;
use App\Eloquent\NewsIdNewsSceneId;
use App\Eloquent\ShoppingCart;
use App\Eloquent\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;


/**
 * Class NewsRepository.
 */
class ShoppingCartRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return ShoppingCart::class;
    }

    public function multiSort ($value) {
        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key == 'user_id') {
                    if ($val == null) {
                        continue;
                    } else {
                        $query->whereHas('user', function (Builder $query) use ($val) {
                            $query->where('name', 'LIKE', '%' . $val . '%');
                        });
                    }
                } else {
                    if ($key == 'updated_from' || $key == 'updated_to') {
                        if (empty($value['updated_from']) || $value['updated_from'] == null) {
                            $value['updated_from'] = '01-01-1900';
                        }
                        if (empty($value['updated_to']) || $value['updated_to'] == null) {
                            $value['updated_to'] = '01-01-2100';
                        }
                            $query->whereBetween('updated_at', [Carbon::parse($value['updated_from']), Carbon::parse($value['updated_to'])]);

                    } elseif($key == 'created_from' || $key == 'created_to') {
                        if (empty($value['created_from']) || $value['created_from'] == null) {
                            $value['created_from'] = '01-01-1900';
                        }
                        if (empty($value['created_to']) || $value['created_to'] == null) {
                            $value['created_to'] = '01-01-2100';
                        }
                        $query->whereBetween('created_at', [Carbon::parse($value['created_from']), Carbon::parse($value['created_to'])]);
                    } else {
                        if (is_null($val)) {
                            $query->where($key, 'LIKE', '%' . '%' . '%');
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
                }
            }
        });

    }
}
