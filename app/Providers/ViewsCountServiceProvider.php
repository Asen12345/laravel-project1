<?php

namespace App\Providers;

use App\Services\ViewsCount\ViewsCountService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ViewsCountServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind('viewscount', function() {
            return new ViewsCountService();
        });
    }
}
