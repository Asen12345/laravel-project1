<?php

namespace App\Jobs;

use DB;
use Exception;
use Mail;
use App\Eloquent\News;
use App\Eloquent\User;
use App\Eloquent\Topic;
use App\Eloquent\Anons;
use App\Eloquent\BlogPost;
use App\Mail\NewsletterSend;
use Illuminate\Bus\Queueable;
use App\Eloquent\NewsletterCount;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewsletterSendJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $email;
    protected $newsNewsletterIds;
    protected $topicId;
    protected $anonsIds;
    protected $blogNewsIds;
    protected $mainNewIds;
    protected $newsletterCountId;

    /**
     * Create a new job instance.
     * @param $newsletterCountId
     * @param $email
     * @param $mainNewIds
     * @param $newsNewsletterIds
     * @param $blogNewsIds
     * @param $anonsIds
     * @param $topicId
     */
    public function __construct($newsletterCountId, $email, $mainNewIds, $newsNewsletterIds, $blogNewsIds, $anonsIds, $topicId = null)
    {
        $this->newsletterCountId  = $newsletterCountId;
        $this->email              = $email;
        $this->topicId            = $topicId;
        $this->anonsIds           = $anonsIds;
        $this->blogNewsIds        = $blogNewsIds;
        $this->mainNewIds         = $mainNewIds;
        $this->newsNewsletterIds  = $newsNewsletterIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $randomKey1            = '2141569819753';
        $randomKey2            = 'slhaw25gwiYIURndf7202';
        $name                  = User::where('email', $this->email)->first()->name ?? 'Пользователь';
        $hash                  = base64_encode($randomKey1 . $this->email . $randomKey2);
        $news                  = News::whereIn('id', $this->newsNewsletterIds)->withCount(['category' => function($query){
            $query->select('name');}])->orderBy('category_count')->get()->mapToGroups(function ($item, $key) {return [$item['category_count'] => $item];});
        $topic                 = Topic::where('id', $this->topicId)->with('subscriber')->first();
        $anons                 = Anons::whereIn('id', $this->anonsIds)->orderBy('date', 'asc')->get();
        $blogNews              = BlogPost::whereIn('id', $this->blogNewsIds)->orderBy('published_at', 'asc')->get();
        $mainNews              = News::whereIn('id', $this->mainNewIds)->orderBy('created_at', 'DESC')->get();
        $newsletterCount       = NewsletterCount::where('id', $this->newsletterCountId)->first();
        $unsubscribeLink       = route('api.user.unsubscribe', ['email' => $this->email, 'hash' => $hash]);

        $idNewsletter = $newsletterCount->id;

        DB::beginTransaction();
        try {
            /*В AppServiceProvider Проверяем сколько осталось задачь => если 0 то send to admin*/
            Mail::send(new NewsletterSend($this->email, $name, $unsubscribeLink, $mainNews, $news, $topic, $blogNews, $anons, $idNewsletter));
            $newsletterCount->increment('mail_count', 1);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Error with send email in NewsletterSendJob message = ' . print_r($e->getMessage()));
        }

    }
}
