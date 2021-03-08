<?php

namespace App\Http\Controllers\Front\Anons;

use App\Eloquent\Anons;
use App\Eloquent\Banner;
use App\Eloquent\BannerPlace;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\Anons\AnonsPageContent;
use Carbon\Carbon;

class AnonsController extends Controller
{

    public function index() {

        $anons = Anons::where('will_end', '>=', Carbon::now())
            ->orderBy('date', 'ASC')
            ->paginate(30);
        return view('front.page.anons.anons', [
            'content_page'  => (new AnonsPageContent())->anonsIndexContent(),
            'anons'         => $anons,
        ]);
    }

    public function anonsPage($id) {

        $anons = Anons::where('id', $id)
            ->where('will_end', '>=', Carbon::now())
            ->first();
        $bannersAnnounce = Banner::whereIn('banner_place_id', [17])
            ->where('blog_announce_id', $anons->id )
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(17)->view_count);

        return view('front.page.anons.anons-page', [
            'content_page'    => (new AnonsPageContent())->anonsPageContent($anons),
            'anons'           => $anons,
            'bannersAnnounce' => $bannersAnnounce,
        ]);
    }

}
