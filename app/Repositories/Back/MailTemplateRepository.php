<?php

namespace App\Repositories\Back;

use App\Eloquent\MailTemplate;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class SettingRepository.
 */
class MailTemplateRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return MailTemplate::class;
    }
}
