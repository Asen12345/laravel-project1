<?php

namespace App\Listeners;

use App\Eloquent\AlertAccount;
use App\Eloquent\User;
use App\Events\NoticeTopicSubscriberAccountEvent;
use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoticeTopicSubscriberAccountListeners
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NoticeTopicSubscriberAccountEvent  $event
     * @return void
     */
    public function handle(NoticeTopicSubscriberAccountEvent $event)
    {
        $settingTemplate = (new MailTemplateRepository())->getByColumn('topic_subscriber', 'template_id');
        $subject = $settingTemplate->subject;

        $userToSend = User::where('id', $event->topicSubscriber->user_id)->first();

        $data = [
            'to_name'    => $userToSend->name,
            'title'      => $event->topicSubscriber->topic->title,
            'link'       => '<a href="' . route('front.page.topic.page', ['url_en' => $event->topicSubscriber->topic->url_en]) . '">Перейти</a>',
        ];

        AlertAccount::create([
            'type'    => 'new_topic_subscriber',
            'user_id' => $userToSend->id,
            'text'    => view('email.content.topic_subscriber', $data),
        ]);
    }
}
