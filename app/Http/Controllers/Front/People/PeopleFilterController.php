<?php

namespace App\Http\Controllers\Front\People;

use App\Eloquent\GeoCity;
use App\Http\PageContent\Frontend\People\Company\CompanyPageContent;
use App\Http\PageContent\Frontend\People\Expert\ExpertPageContent;
use App\Repositories\Frontend\Company\CompanyRepository;
use App\Repositories\Frontend\People\PeopleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeopleFilterController extends Controller
{
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

        $companies = (new PeopleRepository())
            ->filterCompany($request->except('_token', 'sort_by', 'sort_order'))
            ->with('company')
            ->whereHas('company', function ($query) use ($request) {
                $query->where('companies.type_id', $request->id);
            })
            ->where('active', true)
            ->where('block', false)
            ->with(['blog' => function ($query) {
                $query->where('active', true);
            }])
            ->with(['news' =>function ($query) {
                $query->where('published', true);
            }])
            ->withCount(['comments as comments_count' => function($query){
                $query->where('published', true);
            }])
            ->withCount(['news' => function($query){
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

        return view('front.page.people.peoples', [
            'content_page' => (new ExpertPageContent())->expertsPageContent($request->except('_token', 'sort_by', 'sort_order')),
            'companies'    => $companies,
            'city_name'    => $city_name,
            'sort_order'   => $request->sort_order == 'desc' ? 'asc' : 'desc',
            'sort_by'      => $request->sort_by
        ]);
    }
}
