<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewBlogPostAdmin extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;

    /**
     * Create a new message instance.
     *
     * @param $blogPost
     * @param $to_email
     * @param $to_name
     */
    public function __construct($blogPost, $to_email, $to_name)
    {
        $this->post        = $blogPost;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email    = $to_email;
        $this->to_name     = $to_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = 'new_blog_post_admin';

        $data = [
            'title'      => $this->post->title,
            'user_name'  => $this->post->user->name,
            'link'       => '<a href="' . route('admin.posts.edit' , ['id' => $this->post->id]) . '">Перейти</a>',
            'template'   => $template,
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject('На сайте ЛюдиИпотеки.рф новая запись блога')
            ->view('email.new_register', $data);
    }
}
