<?php

namespace App\Console\Commands\Newsletter;

use App\Eloquent\News;
use App\Eloquent\NewsletterCount;
use App\Eloquent\Topic;
use App\Eloquent\Anons;
use App\Eloquent\BlogPost;
use App\Jobs\NewsletterSendJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Eloquent\NotificationSubscriber;

class Newsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Newsletter';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscribersEmails = NotificationSubscriber::get();

        $mainNews = News::where('vip', true)
            ->where('published', true)
            ->get();

        $newsNewsletters = News::where('vip', false)
            ->where('new', true)
            ->where('published', true)
            ->get();

        $topic = Topic::where('published', true)
            ->where('main_topic', true)
            //->where('new', true)
            ->first();

        $blogNews = BlogPost::where('to_newsletter', true)
            ->get();

        /*Вариант с обновление флага -> берется только новые
         * $anons = Anons::where('new', true)
            ->orderBy('date', 'asc')
            ->get();*/
        $anons = Anons::whereDate('date', '>=', Carbon::now())
            ->get();

        /*Создаем запись для отслеживания кол-ва отправленных писем рассылки*/
        $newsletterCountId = NewsletterCount::create();

        /*----*/
        //$pause = env('MAIL_PAUSE');
        //$inter = env('MAIL_PAUSE');
        /*----*/
		
        foreach ($subscribersEmails as $email) {

            NewsletterSendJob::dispatch(
                $newsletterCountId->id,
                $email->email,
                $mainNews->pluck('id')->toArray(),
                $newsNewsletters->pluck('id')->toArray(),
                $blogNews->pluck('id')->toArray(),
                $anons->pluck('id')->toArray(),
                $topic->id ?? null
            )
                ->onQueue('newsletter');
                //->delay(now()->addSeconds($pause));

            /*----*/
            //$pause = $pause + $inter;
            //$inter = $inter + env('MAIL_PAUSE');;
            /*----*/
        }

        foreach ($mainNews as $row) {
            $row->update(['new' => false]);
        }

        foreach ($newsNewsletters as $n) {
            $n->update(['new' => false]);
        }

        if (!empty($topic)){
             $topic->update(['new' => false]);
        }
        
		/*foreach ($blogNews as $row) {
            BlogPost::withoutSyncingToSearch(function () use ($row) {
                $row->update(['new' => false]);
            });
        }*/

		//Комментириум флаг new -> будем брать по дате
        /*foreach ($anons as $row) {
            $row->update(['new' => false]);
        }*/
    }
}
