<?php

namespace App\Http\Controllers;

use App\Eloquent\Banner;
use Aspirin1988\Ruslug\Slug;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function generateSlug (Request $request) {
        $request->validate([
            'text' => 'max:255',
        ]);
        $slug = Str::lower(Slug::make($request->text));
        return $slug;
    }

    /**
     * Return whether previous route name is equal to a given route name.
     *
     * @param string $routeName
     * @return boolean
     */
    function isPreviousRoute(string $routeName) : bool
    {
        $previousRequest = app('request')->create(URL::previous());

        try {
            $previousRouteName = app('router')->getRoutes()->match($previousRequest)->getName();
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            // Exception is thrown if no mathing route found.
            // This will happen for example when comming from outside of this app.
            return false;
        }

        return $previousRouteName === $routeName;
    }
    public function countLink(Request $request)
    {
        if (filter_var($request->val, FILTER_VALIDATE_URL)) {
            $banner = Banner::where('link', $request->val)
                ->where('id', $request->id)->first();
            $banner->increment('click');
        } else {
            abort('405');
        }
    }

    function getPreviousRoute() {
        $route_name = app('router')->getRoutes()->match(app('request')->create(URL::previous()))->getName();
        return $route_name;
    }

    function  checkboxToBoolean($checkbox) {
        if(empty($checkbox)){
            return false;
        } else if (!empty($checkbox) && ($checkbox == 'on')){
            return true;
        } else {
            return $checkbox;
        }

    }

    function addHttp($url) {
        if ($url !== null && !preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }
}
