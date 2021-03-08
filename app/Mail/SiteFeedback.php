<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiteFeedback extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $feedback;
    public $from_email;
    public $from_name;
    /**
     * Create a new message instance.
     *
     * @param $feedback
     */
    public function __construct($feedback)
    {
        $this->feedback    = $feedback;
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
        $feedback = \App\Eloquent\Feedback::find($this->feedback->id);
        $template = $feedback->subjectTemplate;

        $data = [
            'feedback' => $feedback,
            'template' => $template
        ];

        return $this->to($template->email)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.site_feedback', $data);
    }
}
