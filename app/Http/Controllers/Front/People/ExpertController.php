<?php

namespace App\Http\Controllers\Front\People;

use App\Eloquent\GeoCity;
use App\Eloquent\User;
use App\Http\PageContent\Frontend\People\Expert\ExpertPageContent;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Expert\ExpertRepository;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function __construct(){

    }

    public function index(Request $request) {

        if (empty($request->sort_by)) {
            $sortBy = 'last_login_at';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }
        if ($sortBy == 'posts') {
            $sortBy = 'posts_count';
        }

        $expert = (new ExpertRepository())
            ->filterExpert($request->except('_token', 'sort_by', 'sort_order'))
            ->where('permission', 'expert')
            ->where('block', false)
            ->where('active', true)
            ->with(['blog' => function ($query) {
                $query->where('active', true);
            }])
            ->with(['news' =>function ($query) {
                $query->where('published', true);
            }])
            ->withCount(['comments as comments_count' => function($query){
                $query->where('published', true);
            }])
            ->withCount(['blogPosts as posts_count' => function($query){
                $query->where('published', true);
            }])
            ->orderBy($sortBy, $sortOrder)
            ->paginate(30);

        if (!empty($request->city_id)){
            $city_name = GeoCity::where('id', $request->city_id)->first()->title;
        } else {
            $city_name = null;
        }

        return view('front.page.people.expert.experts', [
            'content_page' => (new ExpertPageContent())->expertsPageContent($request->except('_token', 'sort_by', 'sort_order')),
            'experts'    => $expert,
            'city_name'  => $city_name,
            'sort_order' => $request->sort_order == 'desc' ? 'asc' : 'desc',
            'sort_by'    => $request->sort_by
        ]);

    }
}
