<?php

namespace App\Mail;

use App\Eloquent\Researches;
use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedBack extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $from_email;
    public $from_name;
    public $to_email;
    public $to_name;
    public $text;
    public $researchesId;

    /**
     * Create a new message instance.
     *
     * @param $feedbackShop
     * @param $admin
     */
    public function __construct($feedbackShop, $admin)
    {
        $this->from_email   = $feedbackShop->email;
        $this->from_name    = $feedbackShop->name;
        $this->text         = $feedbackShop->text;
        $this->to_name      = $admin->name;
        $this->to_email     = $admin->email;
        $this->researchesId = $feedbackShop->research_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('feedback', 'template_id');

        $researches = Researches::find($this->researchesId);

        $route = route('front.page.shop.researches.category.entry', ['id' => $researches->id]);

        $data = [
            'user_from'  => $this->from_name,
            'email'      => $this->from_email,
            'link'       => '<a href="' . $route . '">Перейти</a>',
            'template'   => $template['template_id'],
            'product'    => $researches->title
        ];


        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
