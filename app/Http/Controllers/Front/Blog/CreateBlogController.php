<?php

namespace App\Http\Controllers\Front\Blog;

use App\Eloquent\BlogPost;
use App\Eloquent\BlogPostTags;
use App\Eloquent\Tag;
use App\Eloquent\User;
use App\Http\PageContent\Frontend\Setting\AccountPageContent;
use App\Repositories\Frontend\Blog\BlogPostRepository;
use App\Repositories\Frontend\Blog\BlogRepository;
use Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateBlogController extends Controller
{
    protected $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваша сессия была закрыта. Войдите в аккаунт'
                ]);
            } else {
                $this->user = auth()->user();
                if ($this->user->active !== true) {
                    return redirect()->back()->withErrors([
                        'error' => 'Учетная запись не одобрена, ожидайте Email письма об активации'
                    ]);
                }
                return $next($request);
            }
        });
    }

    public function createBlog() {
        $tags = Tag::all();
        $user = User::where('id', $this->user->id)->first();
        if (!empty($user->blog)){
            return redirect()->route('front.setting.account', ['type' => 'blog'])->withErrors([
                'error' => 'У вас уже создан блог'
            ]);
        };
        return view('front.page.setting.page.page-blog-create', [
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'blog',
            'tags'                     => $tags,
            'user'                     => $user
        ]);
    }


    public function createPost() {
        $tags = Tag::all();
        $user = User::where('id', $this->user->id)->first();
        $blog = $user->blog;

        if (empty($blog->id)) {
            return redirect()->back()->withErrors([
                'error' => 'У вас не создан блог.'
            ]);
        }
        return view('front.page.setting.page.page-post-create', [
            'content_page'             => (new AccountPageContent())->createPostPageContent(),
            'menu_setting_active'      => 'blog',
            'tags'                     => $tags,
            'user'                     => $user,
            'blog'                     => $blog
        ]);
    }

    public function storePost(Request $request) {
        $this->validatePost($request->all());

        $user = User::where('id', $this->user->id)->first();
        $blog = $user->blog;

        if (empty($blog->id)) {
            return redirect()->back()->withErrors([
                'error' => 'У вас не создан блог.'
            ]);
        }

        if (!empty($request->tags)) {
            foreach ($request->tags as $key => $tag) {
                if (!is_numeric($tag)) {
                    $tags[$key] = $this->saveTag($tag);
                } else {
                    $tags[$key] = $tag;
                }
            }
        }

        $data['user_id']  = $this->user->id;
        $data['title']    = $request->title;
        $data['announce'] = $request->announce;
        $data['text']     = $request->text;
        $data['tags']     = $tags ?? '';

        $post = (new BlogRepository())->savePost($data);

        if (empty($post['error'])) {
            return redirect()
                ->route('front.setting.account', ['type' => 'blog'])
                ->with('success', 'Запись успешно создана');
        } else {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => $post['error']]);
        }
    }

    public function editPost($post_id) {
        $tags = Tag::all();
        $user = User::where('id', $this->user->id)->first();
        $blog = $user->blog;
        $post = BlogPost::where('id', $post_id)
            ->where('blog_id', $blog->id)
            ->with('tags')
            ->first();
        if (empty($post->id)) {
            return abort(404);
        }
        return view('front.page.setting.page.page-post-edit', [
            'content_page'             => (new AccountPageContent())->editPostPageContent($post),
            'menu_setting_active'      => 'blog',
            'tags'                     => $tags,
            'user'                     => $user,
            'blog'                     => $blog,
            'post'                     => $post
        ]);
    }

    public function updatePost(Request $request, $post_id) {
        $this->validatePost($request->all());
        if (!empty($request->tags)){
            foreach ($request->tags as $key => $tag) {
                if (!is_numeric($tag)) {
                    $tags[$key] = $this->saveTag($tag);
                } else {
                    $tags[$key] = $tag;
                }
            }
        } else {
            $tags = null;
        }


        $data['user_id']  = $this->user->id;
        $data['title']    = $request->title;
        $data['announce'] = $request->announce;
        $data['text']     = $request->text;
        $data['tags']     = $tags;

        $post = (new BlogRepository())->updatePost($data, $post_id);

        if (empty($post['error'])) {
            return redirect()
                ->route('front.setting.account', ['type' => 'blog'])
                ->with('success', 'Запись успешно обновлена');
        } else {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => $post['error']]);
        }
    }

    public function storeBlog(Request $request) {
        $this->validateBlog($request->all());
        if (!empty($request->tags)) {
            foreach ($request->tags as $key => $tag) {
                if (!is_numeric($tag)) {
                    $tags[$key] = $this->saveTag($tag);
                } else {
                    $tags[$key] = $tag;
                }
            }
        }

        $data['subject'] = $request->subject;
        $data['user_id']  = $this->user->id;
        $data['title']    = $request->title;
        $data['announce'] = $request->announce;
        $data['text']     = $request->text;
        $data['tags']     = $tags ?? '';

        $post = (new BlogRepository())->saveFirstBlog($data);

        if (empty($post['error'])) {
            return redirect()
                ->route('front.setting.account', ['type' => 'blog'])
                ->with('success', 'Запись успешно создана');
        } else {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => $post['error']]);
        }


    }

    /**
     * @param $post_id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroyPost($post_id) {
        DB::beginTransaction();
        try {
            (new BlogPostRepository())->deleteById($post_id);
            BlogPostTags::where('blog_post_id', $post_id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()->withErrors(['error' => $id_index]);
        }
        return redirect()
            ->route('front.setting.account', ['type' => 'blog'])
            ->with('success', 'Запись успешно удалена');
    }

    public function validateBlog($request){
        $rules = ([
            'subject'   => ['required', 'string', 'max:255'],
            'title'     => ['required', 'string', 'max:255'],
            'announce'  => ['required', 'string'],
            'text'      => ['required', 'string'],

        ]);
        $names = [
            'subject'          => "'Название блога'",
            'title'            => "'Заголовок'",
            'text'             => "'Текст записи'",
            'announce'         => "'Анонс'",
        ];
        $messages = [
        ];
        Validator::make($request, $rules, $messages, $names)->validate();

        return $request;
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
            'announce'         => "'Анонс'",
        ];
        $messages = [
        ];
        Validator::make($request, $rules, $messages, $names)->validate();

        return $request;
    }

    public function saveTag($value){
        $tag = Tag::create([
            'name' => $value
        ]);

        return $tag->id;
    }
}
