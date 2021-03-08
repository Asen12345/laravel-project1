<?php

namespace App\Http\Controllers\AdminPanel\Blog;

use App\Eloquent\Blog;
use App\Http\PageContent\AdminPanel\Blog\BlogPageContent;
use App\Repositories\Back\BlogRepository;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(){

    }

    public function index(Request $request) {

        $parameters = $request->all();

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
        if ($sortBy == 'posts') {
            $sortBy = 'posts_count';
        }
        if ($sortBy == 'permission') {
            $sortBy = 'users.permission';
        }
        if ($sortBy == 'rating') {
            $sortBy = 'votes_count';
        }

        $content = (new BlogPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $blogs = (new BlogRepository())
            ->withCount('posts')
            ->with(['user', 'votes'])
            ->join('users', 'users.id', '=', 'blogs.user_id')
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }]);

        if (count($parameters) > 0) {
            $blogs = $blogs->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->orderBy($sortBy, $sortOrder)->paginate(50);
            $blogs->appends($parameters);
        } else {
            $blogs = $blogs->orderBy($sortBy, $sortOrder)->paginate(50);
        }

        return view('admin_panel.blogs.index', [
            'content'     => $content,
            'blogs'       => $blogs,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function edit(Request $request){

        DB::beginTransaction();
        try {
            if ($request->active !== null) {
                (new BlogRepository())->getById($request->id)
                    ->update([
                        'active' => $request->active,
                    ]);
            } else {
                (new BlogRepository())->getById($request->id)
                    ->update([
                        'subject' => $request->value,
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

    public function delete($id) {
        DB::beginTransaction();
        try{
            $blog = Blog::where('id', $id)->first();
            $blog->votes()->delete();
            $blog->posts()->delete();
            $blog->comments()->delete();
            $blog->delete();
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
}
