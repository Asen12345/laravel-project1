<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\GeoCountry;
use App\Http\PageContent\AdminPanel\Resources\CountriesPageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    public function index(Request $request) {
        if (empty($request->sort_by)) {
            $sortBy = 'position';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $countries = GeoCountry::orderBy($sortBy, $sortOrder)
            ->paginate('50', array('id', 'title', 'title_en', 'position', 'hidden'));


        $content = (new CountriesPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));
        return view('admin_panel.resources.countries', [
            'content' => $content,
            'data'    => $countries,
        ]);
    }
}
