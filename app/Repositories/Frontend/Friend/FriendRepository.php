<?php

namespace App\Repositories\Frontend\Friend;

use App\Eloquent\Friends;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


/**
 * Class FriendRepository.
 */
class FriendRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Friends::class;
    }
}
