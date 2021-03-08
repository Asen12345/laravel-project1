<?php

namespace App\Http\Controllers\AdminPanel\Comment;

use App\Eloquent\Blog;
use App\Eloquent\BlogPostDiscussion;
use App\Http\PageContent\AdminPanel\Comment\CommentsPageContent;
use App\Repositories\Back\CommentRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CommentController extends Controller
{
    public function __construct(){

    }

    public function comments(Request $request, $id = null) {

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
            $sortBy = 'post_count';
        }
        if ($sortBy == 'user') {
            $sortBy = 'post_count';
        }

        $content = (new CommentsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'), $id);

        if (!is_null($id)) {
            $blog = Blog::where('id', $id)->first();
        } else {
            $blog = null;
        }

        $comments = (new CommentRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->where(function ($query) use ($id) {
                if (!empty($id)){
                    $query->where('post_id', $id);
                } else {
                    $query->whereNotNull('post_id');
                }
            })
            ->with(['post', 'user', 'socialProfileUser'])
            ->withCount(['post' => function($query){
                $query->select('title');
            }])
            ->withCount(['user' => function($query){
                $query->select('name');
            }])
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.comments.index', [
            'content'     => $content,
            'comments'    => $comments,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder,
            'blog'        => $blog
        ]);
    }

    public function edit($id){

        $comment = BlogPostDiscussion::where('id', $id)
            ->with('post')
            ->first();

        $content = (new CommentsPageContent())->editPostPageContent();

        return view('admin_panel.comments.edit', [
            'content'     => $content,
            'comment'     => $comment,
        ]);
    }

    public function update(Request $request, $id){
        $rules = ([
            'text' => ['required'],
        ]);
        $messages = [
            'text.required'    => 'Текст комментария обязателен.',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();
        /*Change in request published value from on => boolean*/
        $request['published'] = $this->checkboxToBoolean($request->published);
        $request['anonym'] = $this->checkboxToBoolean($request->anonym);

        DB::beginTransaction();
        try {
            BlogPostDiscussion::where('id', $id)->update($request->only('text', 'published', 'anonym'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return redirect()->back()
            ->with('success', 'Запись успешно обновлена.');

    }

    public function delete($id) {
        DB::beginTransaction();
        try{
            BlogPostDiscussion::where('id', $id)->delete();
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
                (new CommentRepository())->getById($request->id)
                    ->update([
                        'published' => $request->active,
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
