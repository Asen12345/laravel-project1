<?php

namespace App\Http\Controllers\AdminPanel\BlogPost;

use App\Eloquent\Blog;
use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostTags;
use App\Eloquent\Tag;
use App\Http\PageContent\AdminPanel\Posts\PostsPageContent;
use App\Repositories\Back\BlogRepository;
use App\Repositories\Back\PostRepository;
use App\Repositories\Frontend\Blog\BlogPostRepository;
use App\Repositories\Frontend\Blog\TagRepository;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogPostsController extends Controller
{
    public function __construct(){

    }

    public function posts(Request $request, $id = null) {

        if (empty($request->sort_by)) {
            $sortBy = 'created_at';
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
        if ($sortBy == 'subject') {
            $sortBy = 'blog_count';
        }
        if ($sortBy == 'rating') {
            $sortBy = 'votes_count';
        }
        if ($sortBy == 'comments') {
            $sortBy = 'comments_count';
        }
        $content = (new PostsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'), $id);

        if (!is_null($id)) {
            $blog = Blog::where('id', $id)->first();
        } else {
            $blog = null;
        }

        $posts = (new PostRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->where(function ($query) use ($id) {
                if (!empty($id)){
                    $query->where('blog_id', $id);
                } else {
                    $query->whereNotNull('blog_id');
                }
            })
            ->with(['blog'])
            ->withCount('subscribers')
            ->withCount(['blog' => function($query){
                $query->select('subject');
            }])
            ->withCount(['comments'])
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.posts.index', [
            'content'     => $content,
            'posts'       => $posts,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder,
            'blog'        => $blog
        ]);
    }

    public function edit($id){

        $post = (new PostRepository())->getById($id);
        $content = (new PostsPageContent())->editPostPageContent();

        return view('admin_panel.posts.edit', [
            'content'     => $content,
            'post'        => $post,
            'tags'        => $post->tags,
        ]);
    }
    public function create(){
        $tags = Tag::all();
        $content = (new PostsPageContent())->createPostPageContent();
        return view('admin_panel.posts.create', [
            'content'     => $content,
            'tags'        => $tags,
        ]);
    }

    public function store(Request $request)
    {

        $this->validatePost($request->all());

        if (!empty($request->tags)) {
            foreach ($request->tags as $key => $tag) {
                if (!is_numeric($tag)) {
                    $tags[$key] = $this->saveTag($tag);
                } else {
                    $tags[$key] = $tag;
                }
            }
        }

        $data['blog_id']   = $request->blog_id;
        $data['title']     = $request->title;
        $data['announce']  = $request->announce;
        $data['text']      = $request->text;
        $data['published'] = !empty($request->published) ? true : false;
        $data['tags']      = $tags ?? null;

        $post = (new BlogRepository())->savePost($data);

        if (empty($post['error'])) {
            return redirect()
                ->route('admin.posts.all.index')
                ->with('success', 'Запись успешно создана.');
        } else {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => $post['error']]);
        }

    }

    public function saveTag($value){
        $tag = Tag::create([
            'name' => $value
        ]);

        return $tag->id;
    }

    public function validatePost($request){
        $rules = ([
            'title'     => ['required', 'string', 'max:255'],
            'text'      => ['required', 'string'],

        ]);
        $names = [
            'subject'          => "'Название блога'",
            'title'            => "'Заголовок'",
            'text'             => "'Текст записи'",
        ];
        $messages = [
        ];
        Validator::make($request, $rules, $messages, $names)->validate();

        return $request;
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, $id){
        /*Change in request published value from on => boolean*/
        $request['published'] = $this->checkboxToBoolean($request->published);
        $blog_post = BlogPost::where('id', $id)->first();
        if (!empty($request->tags)){
            $tags_array = (new TagRepository())->saveTagsIfNot($request->tags);
        }

        DB::beginTransaction();
        try {
            BlogPostTags::where('blog_post_id', $id)->delete();
            if (!empty($request->tags)) {
                foreach ($tags_array as $tag) {
                    BlogPostTags::create([
                        'blog_post_id' => $id,
                        'tag_id' => $tag
                    ]);
                }
            }
            $blog_post->update($request->except('tags', '_token'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return redirect()->route('admin.posts.all.index')
            ->with('success', 'Запись успешно обновлена.');

    }

    public function delete($id) {
        DB::beginTransaction();
        try{
            $blog_post = BlogPost::where('id', $id)->first();
            $blog_post->tagsRecords()->delete();
            $blog_post->votes()->delete();
            $blog_post->comments()->delete();
            $blog_post->delete();
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

    public function autocomplete(Request $request) {

        $input = $request->input('name');

        $data = Tag::where('name', 'LIKE', '%' . $input . '%')->take(5)->get();
        if ($data->isEmpty()) {
            $data_null[] = [
              'id'   => $input,
              'name' => $input
            ];
            return response()->json((object)$data_null);
        }

        return response()->json($data);
    }

    public function activate(Request $request){

        DB::beginTransaction();
        try {
            if ($request->active !== null) {
                (new BlogPostRepository())->getById($request->id)
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

    public function autoCompleteBlog(Request $request) {
        $input = $request->input('blog');
        //$from_route = $this->getPreviousRoute();

        //if ($from_route == 'front.page.people.experts.index' || $from_route == 'front.page.people.companies.index') {
            $data = Blog::where('subject', 'LIKE', '%' . $input . '%')
                ->select('subject', 'id')->take(5)->get();
            $data = array_merge((array(['title' => 'Нет', 'id' => ''])), $data->toArray());

        //} else {
            /*$data = GeoCity::where(function ($query) use ($input) {
                $query->where('title', 'LIKE', '%' . $input . '%')
                    ->orWhere('title_en', 'LIKE', '%' . $input . '%');
            })
                ->where('country_id', $request->input('country'))
                ->take(5)->get();*/
        //}


        return response()->json($data);
    }
}
