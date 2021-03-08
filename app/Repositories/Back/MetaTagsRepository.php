<?php

namespace App\Repositories\Back;

use App\Eloquent\MailTemplate;
use App\Eloquent\MetaTags;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class SettingRepository.
 */
class MetaTagsRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return MetaTags::class;
    }
}
