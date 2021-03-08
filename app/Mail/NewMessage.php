<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMessage extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subject;
    public $userFormSend;
    public $userToSend;
    public $message;
    public $from_email;
    public $from_name;
    public $to_email;

    /**
     * Create a new message instance.
     *
     * @param $message
     * @param $subject
     * @param $userFormSend
     * @param $userToSend
     */
    public function __construct($message, $subject, $userFormSend, $userToSend)
    {
        $this->message      = $message;
        $this->subject      = $subject;
        $this->userFormSend = $userFormSend;
        $this->userToSend   = $userToSend;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email     = $userToSend->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('new_message', 'template_id');

        $data = [
            'message_body'    => $this->message->body,
            'subject_message' => $this->subject,
            'user_from'       => $this->userFormSend->name,
            'user_to'         => $this->userToSend->name,
            'link'            => '<a href="' . route('front.setting.account.message.page', ['id' => $this->message->thread->id] ) . '">Читать</a>',
            'template'        => $template['template_id'],
        ];

        return $this->to($this->to_email, $this->userToSend->name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
