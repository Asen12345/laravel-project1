<?php

namespace App\Listeners;

use App\Eloquent\AlertAccount;
use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostSubscriber;
use App\Events\NoticeBlogPostAccountEvent;
use App\Repositories\Back\MailTemplateRepository;

class NoticeBlogPostAccountListeners
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
     * @param  NoticeBlogPostAccountEvent  $event
     * @return void
     */
    public function handle(NoticeBlogPostAccountEvent $event)
    {
        $blogPost = BlogPost::find($event->blogPost->id);

        $settingTemplate = (new MailTemplateRepository())->getByColumn('new_post', 'template_id');

        $subscribeUsers = $blogPost->subscribers()
            ->where('blog_post_subscribers.active', true)
            ->get();

        foreach ($subscribeUsers as $row) {
            if (!empty($row->user)) {
                $randomKey1 = '2141569819753';
                $randomKey2 = 'slhaw25gwiYIURndf7202';
                $hash = base64_encode($randomKey1 . $row->user->email . $randomKey2);
                $unsubscribeLink = route('api.user.unsubscribe.blog', ['email' => $row->user->email, 'blog_id' => $row->blog->id, 'hash' => $hash]);

                $data = [
                    'subject' => $settingTemplate->subject,
                    'title' => $event->blogPost->title,
                    'blog' => $row->blog->subject,
                    'anons' => $event->blogPost->announce,
                    'unsubscribe' => '<a href="' . $unsubscribeLink . '">Отписаться</a>',
                    'link' => '<a href="' . route('front.page.blog', ['permission' => $row->user->permission, 'blog_id' => $row->blog->id]) . '">Читать далее</a>',
                ];

                AlertAccount::create([
                    'type' => 'new_post',
                    'user_id' => $row->user_id,
                    'text' => view('email.content.new_post', $data),
                ]);
            }
        }
    }
}
