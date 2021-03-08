<?php

namespace App\Http\Controllers;

use App\Http\PageContent\Frontend\News\NewsPageContent;
use App\Search\MultiSearch;
use Arr;
use Illuminate\Http\Request;
use Validator;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->sort_order)) {
            $sort_order = 'desc';
        } else {
            $sort_order = $request->sort_order;
        }
        if (empty($request->sort_by)) {
            $sort_by = 'created_at';
        } else {
            $sort_by = $request->sort_by;
        }
        $rules = ([
            //'title' => ['required', 'min:5', 'max:255'],
        ]);
        $messages = [
            'title.required'  => 'Поисковый запрос пустой.',
            'title.min'       => 'Длина запроса должна быть больше :min.',
            'title.max'       => 'Длина запроса должна быть меньше :max.',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();

        $results = MultiSearch::search($request->title)->get();

        if ($sort_order == 'desc') {
            $results = $results->sortByDesc($sort_by);
        } else {
            $results = $results->sortBy($sort_by);
        }

        $results = $this->paginateCollection($results,2, $request->page);

        if ($sort_order === 'desc') {
            $sort_order = 'asc';
        } else {
            $sort_order = 'desc';
        }

        if ($sort_by === 'created_at') {
            $sort_by = 'updated_at';
        }  else {
            $sort_by = 'created_at';
        }

        return view('front.page.search.search_result', [
            'content_page'  => (new NewsPageContent())->searchResult(),
            'results'       => $results,
            'sort_order'    => $sort_order,
            'sort_by'       => $sort_by,
        ]);
    }

    function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
