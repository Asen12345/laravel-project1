<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewPassword extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;
    private $who_change;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $who_change
     */
    public function __construct($user, $who_change)
    {
        $this->user        = $user;
        $this->who_change  = $who_change;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email    = $user->email;
        $this->to_name     = $user->name;

    }

    public function build()
    {
        if ($this->who_change == 'password_change_user') {
            $template_view = 'forgot_password_new_password';
        } elseif ($this->who_change == 'password_change_admin') {
            $template_view = 'change_password_from_admin';
        }


        $template = (new MailTemplateRepository())->getByColumn($template_view, 'template_id');

        $data = [
            'name'       => $this->user->name,
            'password'   => $this->user->open_password,
            'email'      => $this->user->email,
            'permission' => $this->user->permission,
            'link'       => '<a href="' . route('front.home') . '">Перейти</a>',
            'template'   => $template['template_id'],
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
