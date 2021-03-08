<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TopicSubscriber extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $topicSubscriber;
    public $to_email;
    public $to_name;
    public $from_email;
    public $from_name;

    /**
     * Create a new message instance.
     *
     * @param $topicSubscriber
     */
    public function __construct($topicSubscriber)
    {
        $this->topicSubscriber = $topicSubscriber;
        $this->to_email        = $topicSubscriber->user->email;
        $this->to_name         = $topicSubscriber->user->name;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('topic_subscriber', 'template_id');

        $data = [
            'to_name'    => $this->to_name,
            'title'      => $this->topicSubscriber->topic->title,
            'link'       => '<a href="' . route('front.page.topic.page', ['url_en' => $this->topicSubscriber->topic->url_en]) . '">Перейти</a>',
            'template'   => $template['template_id'],
        ];


        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
