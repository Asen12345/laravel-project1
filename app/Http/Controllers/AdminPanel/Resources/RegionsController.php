<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\GeoCountry;
use App\Eloquent\GeoRegion;
use App\Http\PageContent\AdminPanel\Resources\RegionsPageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionsController extends Controller
{
    public function index(Request $request) {
        if (empty($request->sort_by)) {
            $sortBy = 'id';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $data = GeoRegion::where('country_id' , $request->id)
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50', array('id', 'title', 'title_en'));

        $country = GeoCountry::where('id' , $request->id)->select('title', 'title_en')->first();

        $content = (new RegionsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'), $request->id);

        return view('admin_panel.resources.regions', [
            'content'   => $content,
            'data'      => $data,
            'country'   => $country,
        ]);
    }
}
