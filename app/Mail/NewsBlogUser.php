<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewsBlogUser extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blog;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;

    /**
     * Create a new message instance.
     *
     * @param $blog
     */
    public function __construct($blog)
    {
        $this->blog        = $blog;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email    = $blog->user->email;
        $this->to_name     = $blog->user->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('blog_activate_admin', 'template_id');

        $data = [
            'title'      => $this->blog->subject,
            'user_name'  => $this->blog->user->name,
            'link'       => '<a href="' . route('front.home') . '">Перейти</a>',
            'template'   => $template['template_id'],
        ];


        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
