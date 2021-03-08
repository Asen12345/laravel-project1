<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordLink extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $link;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $link
     */
    public function __construct($user, $link)
    {
        $this->user        = $user;
        $this->link        = $link;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email    = $user->email;
        $this->to_name     = $user->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('forgot_password_link', 'template_id');

        $data = [
            'name'        => $this->user->name,
            'email'       => $this->user->email,
            'link'        => $this->link,
            'permission'  => $this->user->permission,
            'template'    => $template['template_id'],
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
