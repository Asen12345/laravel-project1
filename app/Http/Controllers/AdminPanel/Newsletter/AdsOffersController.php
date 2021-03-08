<?php

namespace App\Http\Controllers\AdminPanel\Newsletter;

use App\Eloquent\NewsletterAdsOffers;
use App\Eloquent\NewsletterSetting;
use App\Http\PageContent\AdminPanel\Newsletter\NewsletterPageContent;
use App\Repositories\Back\TextPageRepository;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdsOffersController extends Controller
{
    public function index() {

        $content = (new NewsletterPageContent())->adsOffersPageContent();

        $settings = NewsletterAdsOffers::first();
        return view('admin_panel.newsletter.ads_offers', [
            'content'     => $content,
            'settings'    => $settings,
        ]);
    }

    public function update(Request $request) {
        $settings = NewsletterAdsOffers::first();
        if (empty($settings)) {
            //NewsletterSetting::create($request->all());
            NewsletterAdsOffers::create([
                'text' => $request->text,
            ]);
        } else {
            $settings->update($request->all());
        }

        return redirect()->back()->with('success', 'Запись успешно обновлена.');

    }
}
