<?php

namespace App\Repositories\Back\Shop;

use App\Eloquent\Researches;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

/**
 * Class UserRepository.
 */
class ResearchesRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Researches::class;
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key == 'researches_author_id') {
                    if ($val == null) {
                        continue;
                    } else {
                        $query->whereHas('author', function (Builder $query) use ($val) {
                            $query->where('title', 'LIKE', '%' . $val . '%');
                        });
                    }
                } else {
                    if ($key == 'from' || $key == 'to') {
                        if (empty($value['from']) || $value['from'] == null) {
                            $value['from'] = '01-01-1900';
                        }
                        if (empty($value['to']) || $value['to'] == null) {
                            $value['to'] = '01-01-2100';
                        }
                        $query->whereBetween('published_at', [Carbon::parse($value['from']), Carbon::parse($value['to'])]);

                    } elseif ($key == 'price_from' || $key == 'price_to') {
                        if (empty($value['price_from']) || $value['price_from'] == null) {
                            $value['price_from'] = 0;
                        }
                        if (empty($value['price_to']) || $value['price_to'] == null) {
                            $value['price_to'] = 999999999;
                        }
                        $query->whereBetween('price', [$value['price_from'], $value['price_to']]);
                    } else {
                        if (is_null($val)) {
                            $query->where($key, 'LIKE', '%' . '%' . '%');
                        } elseif (Str::contains($key, '_id')) {
                            $query->where($key, $val);
                        } else {
                            $query->where($key, 'LIKE', '%' . $val . '%');
                        }
                    }
                }
            }
        });

    }

}
