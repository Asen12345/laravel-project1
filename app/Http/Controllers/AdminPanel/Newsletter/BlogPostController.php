<?php

namespace App\Http\Controllers\AdminPanel\Newsletter;

use App\Eloquent\BlogPost;
use App\Http\PageContent\AdminPanel\Newsletter\NewsletterPageContent;
use App\Repositories\Back\PostRepository;
use App\Repositories\Frontend\Blog\BlogPostRepository;
use DB;
use Exception;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogPostController extends Controller
{
    public function index(Request $request) {
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
        if ($sortBy == 'name') {
            $sortBy = 'user_count';
        }

        $content = (new NewsletterPageContent())->blogPostContent($request->except('_token', 'sort_by', 'sort_order'));

        $posts = BlogPost::with(['blog', 'user'])
            ->withCount(['blog' => function($query){
                $query->select('subject');
            }])
            ->withCount(['user' => function($query) {
                $query->select('name');
            }])
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.newsletter.posts.index', [
            'content'     => $content,
            'posts'       => $posts,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder,
        ]);

    }

    public function toNewsletter(Request $request){

        DB::beginTransaction();
        try {
            BlogPost::find($request->id)
                ->update([
                    'to_newsletter' => $request->active,
                ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return response()->json(array('success' => 'false', 'error' => $id_index));
        }

        return response()->json(array('success' => 'ok', 'mess' => 'Запись успешно обновлена.'));
    }
}
