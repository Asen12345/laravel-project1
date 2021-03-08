<?php

namespace App\Repositories\Back\News;

use App\Eloquent\News;
use App\Eloquent\NewsIdNewsSceneId;
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
class NewsRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return News::class;
    }

    public function multiSort ($value) {
        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key == 'author_user_id') {
                    if ($val == null) {
                        continue;
                    } else {
                        $query->where(function ($query) use ($val) {
                            $query->whereHas('user', function (Builder $query) use ($val) {
                                $query->where('name', 'LIKE', '%' . $val . '%');
                            })->orWhere('posted', 'LIKE', '%' . str_replace('k', 'c', Str::slug($val)) . '%');
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
                            $query->whereBetween('created_at', [Carbon::parse($value['from']), Carbon::parse($value['to'])]);

                    } else {
                        if (is_null($val)) {
                            $query->where($key, 'LIKE', '%' . '%' . '%');
                        } elseif (Str::contains($key, '_id')) {
                            $query->where($key, $val);
                        }else {
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

    public function createNews(array $data)
    {
        $author = User::where('name', $data['author_user_name'])->first();
        $published    = !empty($data['published']) ? 1 : 0;
        $vip          = !empty($data['vip']) ? 1 : 0;
        $yandex       = !empty($data['yandex']) ? 1 : 0;
        $author_show  = !empty($data['author_show']) ? 1 : 0;
        DB::beginTransaction();
        try {
            $news = $this->model->create([
                'name'             => $data['name'],
                'published'        => $published,
                'title'            => $data['title'] ?? $data['name'],
                'url_ru'           => $data['url_ru'],
                'url_en'           => $data['url_en'],
                'announce'         => $data['announce'] ?? null,
                'text'             => $data['text'],
                'news_category_id' => $data['news_category_id'],
                'source_name'      => $data['source_name'],
                'source_url'       => $data['source_url'],
                'vip'              => $vip,
                'author_user_id'   => $author->id ?? null,
                'author_text_val'  => $data['author_text_val'] ?? null,
                'posted'           => empty($author->id) ? 'redactor' : 'user',
                'yandex'           => $yandex,
                'author_show'      => $author_show,
                'published_at'     => Carbon::now()->toDate(),
            ]);
            if (!empty($data['news_scene_id'])) {
                foreach ($data['news_scene_id'] as $scene_id) {
                    NewsIdNewsSceneId::create([
                        'news_id'       => $news->id,
                        'news_scene_id' => $scene_id
                    ]);
                }
            }
            DB::commit();
            return array(['success' => $news->id]);
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return ['error' => $id_index];
        }


    }

    public function updateNews(array $data, $id) {
        $author = User::where('name', $data['author_user_name'])->first();
        $published = !empty($data['published']) ? 1 : 0;
        $vip       = !empty($data['vip']) ? 1 : 0;
        $yandex    = !empty($data['yandex']) ? 1 : 0;
        $author_show    = !empty($data['author_show']) ? 1 : 0;
        $news = $this->getById($id);
        DB::beginTransaction();
        try {
            $news->update([
                'name'             => $data['name'],
                'published'        => $published,
                'title'            => $data['title'] ?? $data['name'],
                'url_ru'           => $data['url_ru'],
                'url_en'           => $data['url_en'],
                'announce'         => $data['announce'] ?? null,
                'text'             => $data['text'],
                'news_category_id' => $data['news_category_id'],
                'source_name'      => $data['source_name'],
                'source_url'       => $data['source_url'],
                'vip'              => $vip,
                'author_user_id'   => $author->id ?? null,
                'author_text_val'  => $data['author_text_val'] ?? null,
                'posted'           => empty($author->id) ? 'redactor' : 'user',
                'yandex'           => $yandex,
                'author_show'      => $author_show,
                'published_at'     => Carbon::now()->toDate(),
            ]);
            NewsIdNewsSceneId::where('news_id', $id)->delete();
            if (!empty($data['news_scene_id'])) {
                foreach ($data['news_scene_id'] as $scene_id) {
                    NewsIdNewsSceneId::create([
                        'news_id'       => $news->id,
                        'news_scene_id' => $scene_id
                    ]);
                }
            }

            DB::commit();
            return array(['success' => $news->id]);
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return ['error' => $id_index];
        }

    }
}
