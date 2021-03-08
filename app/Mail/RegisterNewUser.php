<?php

namespace App\Mail;

use App\Eloquent\Admin;
use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class RegisterNewUser extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_name;
    public $to_email;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $to_user
     */
    public function __construct($user, $to_user)
    {
        $this->user        = $user;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_name     = $to_user->name;
        $this->to_email    = $to_user->email;

    }

    public function build()
    {

        $template = (new MailTemplateRepository())->getByColumn('user_register', 'template_id');

        $data = [
            'name'       => $this->user->name,
            'password'   => $this->user->open_password,
            'permission' => $this->user->permission,
            'email'      => $this->user->email,
            'link'       => '<a href="' . route('admin.users.index') . '">Перейти</a>',
            'template'   => $template['template_id'],
        ];


        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
