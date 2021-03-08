<?php

namespace App\Observers;

use App\Eloquent\TopicAnswer;
use App\Jobs\NewTopicAnswerAdminJob;

class TopicAnswerObserver
{
    /**
     * Handle the blog post "created" event.
     *
     * @param TopicAnswer $answer
     * @return void
     */
    public function creating(TopicAnswer $answer)
    {

    }

    public function created(TopicAnswer $answer)
    {
        /*Запускаем воркер для создания jobs (отпускаем запрос без задержки -> там уже логика)
        * один job - 1 письмо
        */
        NewTopicAnswerAdminJob::dispatch($answer);
    }

    /**
     * Handle the blog post "updated" event.
     *
     * @param TopicAnswer $answer
     * @return void
     */
    public function updated(TopicAnswer $answer)
    {
        //
    }

    public function updating(TopicAnswer $answer)
    {
       //
    }

    /**
     * Handle the blog post "deleted" event.
     *
     * @param TopicAnswer $answer
     * @return void
     */
    public function deleted(TopicAnswer $answer)
    {
        //
    }

    /**
     * Handle the blog post "restored" event.
     *
     * @param TopicAnswer $answer
     * @return void
     */
    public function restored(TopicAnswer $answer)
    {
        //
    }

    /**
     * Handle the blog post "force deleted" event.
     *
     * @param TopicAnswer $answer
     * @return void
     */
    public function forceDeleted(TopicAnswer $answer)
    {
        //
    }
}
