<?php

namespace App\Http\Controllers\AdminPanel\Newsletter;

use App\Eloquent\Anons;
use App\Eloquent\BlogPost;
use App\Eloquent\News;
use App\Eloquent\NewsCategory;
use App\Eloquent\NewsletterAdsOffers;
use App\Eloquent\NewsletterSetting;
use App\Eloquent\Topic;
use App\Http\PageContent\AdminPanel\Newsletter\NewsletterPageContent;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $content = (new NewsletterPageContent())->settingPageContent();
        $settings = NewsletterSetting::first();

        return view('admin_panel.newsletter.setting', [
            'content'     => $content,
            'settings'    => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $settings = NewsletterSetting::first();
        if (empty($settings)) {
            NewsletterSetting::create([
                'weekdays'         => $request->weekdays,
                'send_time'        => Carbon::parse($request->send_time)->toTimeString(),
                'email'            => $request->email,
                'footer_text'      => $request->footer_text,
                'unsubscribe_text' => $request->unsubscribe_text,
            ]);
        } else {
            $settings->update($request->all());
        }
        return redirect()->back()->with('success', 'Запись успешно обновлена.');
    }

    public function showTemplate()
    {
        $content = (new NewsletterPageContent())->templateShowPageContent();
        $footer = NewsletterSetting::first();
        return view('admin_panel.newsletter.template_show', [
            'content'     => $content,
            'footer'      => $footer,
        ]);
    }

    public function showNewsletter() {

        $newsletterAdsAdnOffers = NewsletterAdsOffers::first();

        $mainNews = News::where('vip', true)
            ->where('published', true)->orderBy('created_at', 'DESC')->get();

        $categoryNewsWithNews = NewsCategory::with(['news' => function($query) {
            $query->where('vip', false)
                ->where('new', true)
                ->where('published', true)
				->orderBy('created_at', 'DESC');
        }])->get();

        $topic = Topic::where('published', true)
            ->where('main_topic', true)
            //->where('new', true)
            ->with('subscriber')
            ->first();

        $blogsNews = BlogPost::where('to_newsletter', true)
			->orderBy('created_at', 'DESC')
            ->get();

        $anons = Anons::where('new', true)->orderBy('date', 'asc')->get();

        $footer = NewsletterSetting::first();

        return view('admin_panel.newsletter.show', [
            'content'                => (new NewsletterPageContent())->showNewsletter(),
            'newsletterAdsAdnOffers' => $newsletterAdsAdnOffers,
            'mainNews'               => $mainNews,
            'categoryNewsWithNews'   => $categoryNewsWithNews,
            'topic'                  => $topic,
            'blogsNews'              => $blogsNews,
            'anons'                  => $anons,
            'footer'                 => $footer,
        ]);
    }
}
