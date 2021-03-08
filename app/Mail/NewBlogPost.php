<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewBlogPost extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;

    /**
     * Create a new message instance.
     *
     * @param $blogPost
     * @param $to_email
     */
    public function __construct($blogPost, $to_email)
    {
        $this->post        = $blogPost;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email    = $to_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('new_post', 'template_id');

        $randomKey1 = '2141569819753';
        $randomKey2 = 'slhaw25gwiYIURndf7202';
        $hash = base64_encode($randomKey1 . $this->to_email . $randomKey2);
        $unsubscribeLink = route('api.user.unsubscribe.blog', ['email' => $this->to_email, 'blog_id' => $this->post->blog->id , 'hash' => $hash]);

        $subject = str_replace('{{ $title }}', $this->post->title, $template->subject);

        $data = [
            'blog'        => $this->post->blog->subject,
            'title'       => $this->post->title,
            'anons'       => $this->post->announce,
            'unsubscribe' => '<a href="' . $unsubscribeLink . '">Отписаться</a>',
            'link'        => '<a href="' . route('front.page.blog', ['permission' => $this->post->blog->user->permission, 'blog_id' => $this->post->blog->id]) . '">Читать далее</a>',
            'template'    => $template['template_id'],
        ];

        return $this->to($this->to_email)
            ->from($this->from_email)
            ->subject($subject)
            ->view('email.new_register', $data);
    }
}
