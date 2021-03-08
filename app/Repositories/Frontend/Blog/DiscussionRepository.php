<?php

namespace App\Repositories\Frontend\Blog;

use App\Eloquent\BlogPostDiscussion;
use App\Eloquent\UserNotifyComment;
use DB;
use Exception;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class DiscussionRepository.
 */
class DiscussionRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BlogPostDiscussion::class;
    }

    public function sendComment($array) {
        DB::beginTransaction();
        try {
            $post = $this->model->create($array);
            UserNotifyComment::updateOrCreate(
                [
                    'user_id'      => $array['user_id'],
                    'blog_post_id' => $array['post_id']
                ],
                [
                    'notify'      => $array['notify_new_comment']
                ]
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return ['error' => $id_index];
        }

        return $post;

    }
}
