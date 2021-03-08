<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Eloquent\NewsletterSetting;
use App\Eloquent\NewsletterAdsOffers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewsletterSend extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $from_email;
    public $subject;
    public $from_name;
    public $to_email;
    public $to_name;
    public $unsubscribe_link;
    public $mainNews;
    public $news;
    public $topic;
    public $blogsNews;
    public $anons;
    public $idNewsletter;

    /**
     * Create a new message instance.
     *
     * @param $to_email
     * @param $to_name
     * @param $unsubscribe_link
     * @param $mainNews
     * @param $news
     * @param $topic
     * @param $blogsNews
     * @param $anons
     * @param $idNewsletter
     */
    public function __construct($to_email, $to_name, $unsubscribe_link, $mainNews, $news, $topic, $blogsNews, $anons, $idNewsletter)
    {
        $this->from_email           = config('mail.from.address');
        $this->from_name            = config('mail.from.name');
        $this->to_email             = $to_email;
        $this->to_name              = $to_name;
        $this->subject              = 'ЛюдиИпотеки.рф – Новости рынка и компаний (#' . $idNewsletter . ').';
        $this->unsubscribe_link     = $unsubscribe_link;
        $this->mainNews             = $mainNews;
        $this->news                 = $news;
        $this->topic                = $topic;
        $this->blogsNews            = $blogsNews;
        $this->anons                = $anons;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $newsletterAdsAdnOffers = NewsletterAdsOffers::first();
        $footer = NewsletterSetting::first();

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($this->subject)
            ->view('email.newsletter_send', [
                'link'                   => $this->unsubscribe_link,
                'news'                   => $this->news,
                'topic'                  => $this->topic,
                'anons'                  => $this->anons,
                'footer'                 => $footer,
                'mainNews'               => $this->mainNews,
                'blogsNews'              => $this->blogsNews,
                'newsletterAdsAdnOffers' => $newsletterAdsAdnOffers,
            ]);
    }
}
