<?php

namespace App\Providers;

use App\Http\ViewComposers\AnalyticResearchComposer;
use App\Http\ViewComposers\CompanyTypeComposer;
use App\Http\ViewComposers\FilterExpertComposer;
use App\Http\ViewComposers\FooterComposer;
use App\Http\ViewComposers\ForgotPasswordComposer;
use App\Http\ViewComposers\HomeBannersComposer;
use App\Http\ViewComposers\LayoutAppComposer;
use App\Http\ViewComposers\NavigationComposer;
use App\Http\ViewComposers\NewsSceneSidebarComposer;
use App\Http\ViewComposers\NoticeComposer;
use App\Http\ViewComposers\ShopMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Top menu
        View::composer('front.partials.menu-main', NavigationComposer::class);
        View::composer('front.page.people.expert.chunk.filter-experts', FilterExpertComposer::class);
        View::composer('front.page.people.company.chunk.filter-company', FilterExpertComposer::class);
        View::composer('front.layouts.app', LayoutAppComposer::class);
        View::composer('front.partials.menu-footer', FooterComposer::class);
        View::composer('front.sidebar_module.sidebar-company', CompanyTypeComposer::class);
        View::composer('front.sidebar_module.sidebar-scene-news', NewsSceneSidebarComposer::class);
        View::composer('front.sidebar_module.sidebar-analytics', AnalyticResearchComposer::class);
        View::composer('front.partials.forgot-password', ForgotPasswordComposer::class);
        View::composer('*', HomeBannersComposer::class);
        View::composer('front.*', NoticeComposer::class);
        View::composer('front.sidebar_module.shop-category-menu-block', ShopMenuComposer::class);
        View::composer('front.partials.mobile-menu', ShopMenuComposer::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
