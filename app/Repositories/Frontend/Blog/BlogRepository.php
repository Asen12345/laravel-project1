<?php

namespace App\Repositories\Frontend\Blog;

use App\Eloquent\Blog;
use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostTags;
use App\Eloquent\UserSocialProfile;
use Carbon\Carbon;
use DB;
use Exception;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


/**
 * Class BlogRepository.
 */
class BlogRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Blog::class;
    }

    public function saveFirstBlog($data) {
        DB::beginTransaction();
        try {
            $blog = Blog::create([
                'user_id' => $data['user_id'],
                'subject' => $data['subject'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $blog_post = BlogPost::create([
                'blog_id'    => $blog->id,
                'title'      => $data['title'],
                'announce'   => $data['announce'],
                'text'       => $data['text'],
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

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    public function savePost($data){
        DB::beginTransaction();
        try {
            $blog = auth()->user()->blog;
            $firstBlogPost = $blog->firstPost;
            if (!empty($firstBlogPost)) {
                if ($blog->active == true && $firstBlogPost->published == true) {
                    $published = true;
                } else {
                    $published = false;
                }
            }
            $blog_post = BlogPost::create([
                'blog_id'    => $blog->id,
                'published'  => $published,
                'title'      => $data['title'],
                'announce'   => $data['announce'],
                'text'       => $data['text'],
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

    public function updatePost($data, $post_id) {
        DB::beginTransaction();
        try {
            $blog = auth()->user()->blog;
             BlogPost::where('id', $post_id)
                ->update([
                    'blog_id'    => $blog->id,
                    'title'      => $data['title'],
                    'announce'   => $data['announce'],
                    'text'       => $data['text'],
                    'updated_at' => Carbon::now(),
                ]);
            BlogPostTags::where('blog_post_id', $post_id)->delete();
            if (!empty($data['tags'])){
                foreach ($data['tags'] as $tag) {
                    BlogPostTags::create([
                        'blog_post_id' => $post_id,
                        'tag_id'       => $tag
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
