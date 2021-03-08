<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\GeoCity;
use App\Http\PageContent\AdminPanel\Resources\CityPageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
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

        $cities = GeoCity::whereNotNull('id')
            ->withCount(['region' => function($query){
                $query->select('title');
            }])
            ->withCount(['country' => function($query){
                $query->select('title');
            }])
            ->with(['region', 'country'])
            ->orderBy($sortBy, $sortOrder)
            ->paginate(50);
        $content = (new CityPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        return view('admin_panel.resources.city', [
            'content'   => $content,
            'cities'    => $cities,
        ]);
    }
}
