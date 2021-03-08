<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\NoticeMessageAccountEvent' => [
            'App\Listeners\NoticeMessageAccountListeners',
        ],
        'App\Events\NoticeFriendAccountEvent' => [
            'App\Listeners\NoticeFriendAccountListeners',
        ],
        'App\Events\NoticeBlogPostAccountEvent' => [
            'App\Listeners\NoticeBlogPostAccountListeners',
        ],
        'App\Events\NoticePostCommentAccountEvent' => [
            'App\Listeners\NoticePostCommentAccountListeners',
        ],
        'App\Events\NoticeTopicSubscriberAccountEvent' => [
            'App\Listeners\NoticeTopicSubscriberAccountListeners',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
