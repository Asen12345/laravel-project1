<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinishNewsletterAdmin extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $from_email;
    public $from_name;
    public $to_email;
    public $to_name;
    public $idNewsletter;
    public $countUsers;
    public $dateNewsletter;

    /**
     * Create a new message instance.
     *
     * @param $settingEmail
     * @param $idNewsletter
     * @param $countUsers
     * @param $dateNewsletter
     */
    public function __construct($settingEmail, $idNewsletter, $countUsers, $dateNewsletter)
    {
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email       = $settingEmail;
        $this->to_name        = 'Админу';
        $this->countUsers     = $countUsers;
        $this->idNewsletter   = $idNewsletter;
        $this->dateNewsletter = $dateNewsletter;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'template'       => 'finish_newsletter_admin',
            'countUsers'     => $this->countUsers,
            'idNewsletter'   => $this->idNewsletter,
            'dateNewsletter' => $this->dateNewsletter,
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject('Рассылка #' . $this->idNewsletter)
            ->view('email.new_register', $data);
    }
}
