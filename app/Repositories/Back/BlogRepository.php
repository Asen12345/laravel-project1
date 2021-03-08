<?php

namespace App\Repositories\Back;

use App\Eloquent\Blog;
use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostTags;
use App\Repositories\Traits\Join;
use App\Repositories\Traits\WithCount;
use Carbon\Carbon;
use DB;
use Exception;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

/**
 * Class BlogRepository.
 */
class BlogRepository extends BaseRepository
{
    use WithCount, Join;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Blog::class;
    }

    public function multiSort ($value) {
        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'active' && $val == null){
                    continue;
                }
                if (is_null($val)) {
                    $query->where($key, 'LIKE', '%' .''. '%');
                } elseif (Str::contains($key, '_id')) {
                    $query->where($key, $val);
                } else {
                    if ($key === 'active') {
                        $query->where(function ($query) use ($val) {
                            $query->where('blogs.active', '=', $val);
                        });
                    } else {
                        $query->where($key, 'LIKE', '%' . $val . '%');
                    }
                }
            }
        });

    }

    public function savePost($data){
        DB::beginTransaction();
        try {
            $blog_post = BlogPost::create([
                'blog_id'    => $data['blog_id'],
                'title'      => $data['title'],
                'announce'   => $data['announce'],
                'text'       => $data['text'],
                'published'  => $data['published'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
            if (!empty($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    BlogPostTags::create([
                        'blog_post_id' => $blog_post->id,
                        'tag_id' => $tag
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return (['error' => $id_index]);
        }
    }
}
