<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailingAdmin extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mailingUser;
    public $to_email;
    public $to_name;
    public $from_email;
    public $from_name;

    /**
     * Create a new message instance.
     *
     * @param $mailingUser
     */
    public function __construct($mailingUser)
    {
        $this->mailingUser = $mailingUser;
        $this->to_email    = $mailingUser->user->email;
        $this->to_name     = $mailingUser->user->name;
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
        $template = (new MailTemplateRepository())->getByColumn('new_message_from_admin', 'template_id');

        $data = [
            'message_body' => $this->mailingUser->mail->text,
            'subject'      => $this->mailingUser->mail->subject,
            'user_from'    => 'Администрация',
            'user_to'      => $this->mailingUser->user->name,
            'link'         => '<a href="' . route('front.setting.account', ['type' => 'message']) . '">Читать</a>',
            'template'     => $template['template_id'],
        ];

        if (!empty($this->mailingUser->mail->subject)) {
            $subject = $this->mailingUser->mail->subject;
        } else {
            $subject = $template->subject;
        }



        if (!empty($this->mailingUser->mail->file_patch)) {

            $pathToFile = public_path() . '/storage/file_upload/' . $this->mailingUser->mail->file_patch;

            return $this->to($this->to_email, $this->to_name)
                ->from($this->from_email, $this->from_name)
                ->subject($subject)
                ->attach($pathToFile)
                ->view('email.new_register', $data);

        } else {
            return $this->to($this->to_email, $this->to_name)
                ->from($this->from_email, $this->from_name)
                ->subject($subject)
                ->view('email.new_register', $data);
        }






    }
}
