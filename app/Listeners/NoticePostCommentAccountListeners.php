<?php

namespace App\Listeners;

use App\Eloquent\AlertAccount;
use App\Eloquent\User;
use App\Eloquent\UserNotifyComment;
use App\Events\NoticePostCommentAccountEvent;
use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoticePostCommentAccountListeners
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
     * @param  NoticePostCommentAccountEvent  $event
     * @return void
     */
    public function handle(NoticePostCommentAccountEvent $event)
    {
        $settingTemplate = (new MailTemplateRepository())->getByColumn('new_comment_in_blog', 'template_id');
        $subject = $settingTemplate->subject;

        $userFormSend = User::where('id', $event->blogPostDiscussion->user_id)->first();
        $userToSend = User::where('id', $event->blogPostDiscussion->blog->user->id)->first();

        $data = [
            'blog_name' => $event->blogPostDiscussion->blog->subject,
            'post_name' => $event->blogPostDiscussion->post->title,
            'user_from' => $userFormSend->name,
            'user_to'   => $userToSend->name,
            'link' => '<a href="' . route('front.page.post', ['permission' => $userFormSend->permission, 'blog_id' => $event->blogPostDiscussion->blog->id, 'post_id' => $event->blogPostDiscussion->post->id]) . '">Читать</a>',
        ];

        if ($userFormSend->id !== $userToSend->id) {
            AlertAccount::create([
                'type' => 'new_comments',
                'user_id' => $userToSend->id,
                'text' => view('email.content.new_comment_in_blog', $data),
            ]);
        }

        $commentSubscribers = UserNotifyComment::where('notify', true)
            ->where('blog_post_id', $event->blogPostDiscussion->post->id)->get();

        foreach ($commentSubscribers as $userId) {
            if ($userId->user_id !== $userFormSend->id)
            AlertAccount::create([
                'type'    => 'new_comments',
                'user_id' => $userId->user_id,
                'text'    => view('email.content.new_comment_in_blog', $data),
            ]);
        }

    }
}
