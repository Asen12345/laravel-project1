<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewBlogPostComment extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $from_email;
    public $from_name;
    public $to_email;
    public $post;
    public $user_name;
    public $text;
    public $blog;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $post
     * @param $blog
     * @param $text
     * @param $userName
     * @param $user
     */
    public function __construct($post, $blog, $text, $userName, $user)
    {
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->post         = $post;
        $this->user_name    = $userName;
        $this->text         = $text;
        $this->blog         = $blog;
        $this->user         = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('new_comment_in_blog', 'template_id');

        $data = [
            'blog_name'      => $this->blog->subject,
            'post_name'      => $this->post->title,
            'user_from'      => $this->user_name,
            'user_to'        => $this->user->name,
            'link'           => '<a href="' . route('front.page.post', ['permission' => $this->user->permission, 'blog_id' => $this->blog->id, 'post_id' => $this->post->id]) . '">Читать</a>',
            'template'       => $template['template_id'],
        ];

        return $this->to($this->user->email, $this->user->name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
