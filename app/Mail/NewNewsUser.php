<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewNewsUser extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $news;
    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;

    /**
     * Create a new message instance.
     *
     * @param $news
     */
    public function __construct($news)
    {
        $this->news        = $news;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email    = $news->user->email;
        $this->to_name     = $news->user->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = (new MailTemplateRepository())->getByColumn('news_activate_admin', 'template_id');

        if ($this->news->category->parent_id == 0) {
            $link = '<a href="' . route('front.page.news.category.entry', ['url_section' => $this->news->category->url_en, 'url_news' => $this->news->url_en]) . '">' . $this->news->name . '</a>';
        } else {
            $link = '<a href="' . route('front.page.news.sub_category.entry', ['url_section' => $this->news->category->parent->url_en, 'url_sub_section' => $this->news->category->url_en, 'url_news' => $this->news->url_en]) . '">' . $this->news->name . '</a>';
        }

        $data = [
            'title'      => $this->news->name,
            'link'       => $link,
            'user_name'  => $this->news->user->name,
            'template'   => $template['template_id'],
        ];


        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
