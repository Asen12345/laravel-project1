<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTopicAnswerAdmin extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $answer;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;

    /**
     * Create a new message instance.
     *
     * @param $answer
     * @param $to_email
     * @param $to_name
     */
    public function __construct($answer, $to_email, $to_name)
    {
        $this->answer      = $answer;
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
            'title'      => $this->answer->topic->title,
            'user_name'  => $this->answer->user->name,
            'link'       => '<a href="' . route('admin.answer.edit' , ['id' => $this->answer->id]) . '">Перейти</a>',
            'template'   => $template,
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject('На сайте ЛюдиИпотеки новый ответ в теме.')
            ->view('email.new_register', $data);
    }
}
