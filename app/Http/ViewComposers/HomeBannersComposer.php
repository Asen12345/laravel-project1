<?php

namespace App\Http\ViewComposers;

use App\Eloquent\Banner;
use App\Eloquent\BannerPlace;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeBannersComposer
{
    public function compose(View $view)
    {
        $bannersHomeExpert = Banner::where('banner_place_id', 14)
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(14)->view_count);

        $bannersHomeCompany = Banner::where('banner_place_id', 15)
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(15)->view_count);

        $bannersSideBar = Banner::where('banner_place_id', 5)
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(5)->view_count);

        $bannersHeader = Banner::where('banner_place_id', 13)
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(13)->view_count);

        $bannerNewsUp = Banner::where('banner_place_id', 6)
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(6)->view_count);

        $bannerNewsDown = Banner::where('banner_place_id', 16)
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(16)->view_count);

        return $view->with([
            'bannersHomeExpert'   => $bannersHomeExpert,
            'bannersHomeCompany'  => $bannersHomeCompany,
            'bannersSideBar'      => $bannersSideBar,
            'bannersHeader'       => $bannersHeader,
            'bannerNewsUp'        => $bannerNewsUp,
            'bannerNewsDown'      => $bannerNewsDown,
        ]);

    }
}