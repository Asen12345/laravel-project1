<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewResearches extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $researches;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;

    /**
     * Create a new message instance.
     *
     * @param $researches
     * @param $to_email
     */
    public function __construct($researches, $to_email)
    {
        $this->researches = $researches;
        $this->to_email   = $to_email;
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
        $template = (new MailTemplateRepository())->getByColumn('new_research', 'template_id');

        $randomKey1 = '2141569819753';
        $randomKey2 = 'slhaw25gwiYIURndf7202';
        $hash = base64_encode($randomKey1 . $this->to_email . $randomKey2);
        $unsubscribeLink = '<a href="' . route('api.user.unsubscribe.researches', ['email' => $this->to_email, 'author_id' => $this->researches->author->id , 'hash' => $hash]) . '">' . 'Отписаться'  . '</a></h2>';

        $link = '<h2><a href="' . route('front.page.shop.researches.category.entry', ['id' => $this->researches->id]) . '">' .  $this->researches->title . '</a></h2>';

        $data = [
            'title'       => $this->researches->title,
            'link'        => $link,
            'author'      => $this->researches->author->title,
            'unsubscribe' => $unsubscribeLink,
            'template'    => $template['template_id'],
        ];


        return $this->to($this->to_email)
            ->from($this->from_email)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
