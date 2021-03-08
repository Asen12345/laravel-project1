<?php

namespace App\Http\Controllers\AdminPanel\Subscriber;

use App\Eloquent\BlogPostDiscussion;
use App\Eloquent\BlogPostSubscriber;
use App\Http\PageContent\AdminPanel\Subscriber\SubscriberPageContent;
use App\Repositories\Back\CommentRepository;
use App\Repositories\Back\SubscriberRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    public function index(Request $request, $blog_id = null)
    {
        if (empty($request->sort_by)) {
            $sortBy = 'id';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }
        if ($sortBy == 'title') {
            $sortBy = 'blog_count';
        }

        $content = (new SubscriberPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        if (empty($blog_id)){
            $records = (new SubscriberRepository())
                ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->with(['blog'])
                ->withCount(['blog' => function ($query) {
                    $query->select('subject');
                }])
                ->orderBy($sortBy, $sortOrder)
                ->paginate('50');
        } else {
            $records = (new SubscriberRepository())
                ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->where('blog_id', $blog_id)
                ->with(['blog'])
                ->withCount(['blog' => function ($query) {
                    $query->select('subject');
                }])
                ->orderBy($sortBy, $sortOrder)
                ->paginate('50');
        }


        $filter_data = $request->except('_token');

        return view('admin_panel.subscriber.index', [
            'content' => $content,
            'records' => $records,
            'filter_data' => $filter_data,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);
    }



    public function delete($id) {
        DB::beginTransaction();
        try{
            BlogPostSubscriber::where('id', $id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()->back()
            ->with('success', 'Запись успешно удалена.');
    }

    public function activate(Request $request){

        DB::beginTransaction();
        try {
            if ($request->active !== null) {
                (new SubscriberRepository())->getById($request->id)
                    ->update([
                        'active' => $request->active,
                    ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return response()->json(array('success' => 'false', 'error' => $id_index));
        }

        return response()->json(array('success' => 'ok', 'mess' => 'Запись успешно обновлена.'));
    }
}
