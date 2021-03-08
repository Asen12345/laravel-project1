<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewNewsAdmin extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $news;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;
    public $to_user;

    /**
     * Create a new message instance.
     *
     * @param $news
     * @param $to_user
     */
    public function __construct($news, $to_user)
    {
        $this->news        = $news;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_name     = $to_user->name;
        $this->to_email    = $to_user->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('new_news_admin', 'template_id');

        $data = [
            'title'      => $this->news->name,
            'user_name'  => $this->news->user->name ?? 'Не авторизованный пользователь.',
            'link'       => '<a href="' . route('admin.news.edit', ['id' => $this->news->id]) . '">Перейти</a>',
            'template'   => $template['template_id'],
        ];


        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
