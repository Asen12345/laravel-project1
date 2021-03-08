<?php

namespace App\Http\ViewComposers;

use App\Eloquent\CompanyType;
use App\Eloquent\DataTemplate;
use App\Eloquent\GeoCountry;
use Illuminate\View\View;

class ForgotPasswordComposer
{
    public function compose(View $view) {

        $text = DataTemplate::where('page', 'password_recovery')->first();
        return $view->with('text', $text);

    }
}