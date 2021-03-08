<?php

namespace App\Http\Controllers\Front\Shop;

use App\Eloquent\ResearchAuthorSubscriber;
use App\Eloquent\ResearchesAuthor;
use App\Eloquent\ShopCategory;
use App\Http\PageContent\Frontend\Shop\ResearchesPageContent;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorsResearchesController extends Controller
{
    protected $category;
    protected $model_cat_sub;
    protected $model_cat;
    protected $array_child_cat_id;

    public function __construct(Request $request) {
        if (!empty($request->url_section) || !empty($request->url_sub_section)) {
            $request->validate([
                'url_section'     => 'string|max:255',
                'url_sub_section' => 'string|max:255',
            ]);
            if (!empty($request->url_sub_section)) {
                $this->category = ShopCategory::where('url_en', $request->url_sub_section)->first();
                $this->model_cat_sub = $this->category;
                $this->model_cat = ShopCategory::where('url_en', $request->url_section)->first();
            } else {
                $this->category = ShopCategory::where('url_en', $request->url_section)->first();
                $this->model_cat = $this->category;
                /*array ID child category*/
                $this->array_child_cat_id =  $this->category->child->pluck('id')->toArray();
            }
        }
    }

    public function index()
    {
        $authors = ResearchesAuthor::whereNotNull('id')->orderBy('created_at', 'DESC')->paginate(30);

        foreach ($authors as $author){
            $author['subscribe']  = auth()->check() ? $author->subscribers->where('active', true)->contains('user_id', auth()->user()->id) : null;
        }

        return view('front.page.shop.authors-page', [
            'content_page'  => (new ResearchesPageContent())->researchAuthorsPage($this->model_cat, $this->model_cat_sub),
            'authors'       => $authors,
        ]);
    }

    public function pageAuthor($id)
    {
        $author     = ResearchesAuthor::find($id);
        $researches = $author->researches()->orderBy('published_at', 'DESC')->paginate(30);

        $author['subscribe']  = auth()->check() ? $author->subscribers->where('active', true)->contains('user_id', auth()->user()->id) : null;
        return view('front.page.shop.author-page', [
            'content_page'  => (new ResearchesPageContent())->researchAuthorPage($this->model_cat, $this->model_cat_sub, $author),
            'author'        => $author,
            'researches'    => $researches,
        ]);
    }

    public function researchesAuthor($id)
    {
        $author     = ResearchesAuthor::find($id);
        $researches = $author->researches()->orderBy('published_at', 'DESC')->paginate(30);

        return view('front.page.shop.researches-page', [
            'content_page'  => (new ResearchesPageContent())->researchAuthorPage($this->model_cat, $this->model_cat_sub, $author),
            'author'        => $author,
            'researches'    => $researches,
        ]);
    }


    public function subscribeAuthorForm(Request $request, $id)
    {
        $hasAuthorSubscribeRecord = ResearchAuthorSubscriber::where('email', $request->email)
            ->where('author_id', $id)->first();

        if (!empty($hasAuthorSubscribeRecord)){
            return redirect()->back()->withErrors(['Вы уже подписаны']);
        } else {
            DB::beginTransaction();
            try {
                ResearchAuthorSubscriber::create([
                    'user_id'   => null,
                    'author_id' => $id,
                    'active'    => true,
                    'email'     => $request->email,
                ]);
                DB::commit();
            } catch (Exception $e) {
                $error = $e->getMessage();
                DB::rollBack();
                return redirect()->back()->withErrors([$error]);
            }
        }

        return redirect()->back()->with('success', 'Вы успешно подписались на исследования автора');
    }

    /*Ajax unsubscribe blog*/
    public function subscribeAuthor(Request $request)
    {

        if ($request->type === 'unsubscribe') {
            $id = $request->val;
            $subscribeRecord = ResearchAuthorSubscriber::where('user_id', auth()->user()->id)
                ->where('author_id', $id)
                ->first();
            DB::beginTransaction();
            try {
                $subscribeRecord->update([
                    'active' => false,
                ]);
                DB::commit();
            } catch (Exception $e) {
                $error = $e->getMessage();
                DB::rollBack();
                return response()->json(['error' => $error]);
            }
            return response()->json(['success' => 'ok', 'id' => $id]);

        } elseif ($request->type === 'subscribe') {
            $id = $request->val;
            $hasAuthorSubscribeRecord = ResearchAuthorSubscriber::where('user_id', auth()->user()->id)
                ->where('author_id', $id)->first();

            if (!empty($hasAuthorSubscribeRecord)){
                if($hasAuthorSubscribeRecord->active == true) {

                    return response()->json(['error' => 'Вы уже подписаны на этого автора']);

                } else {

                    DB::beginTransaction();
                    try {
                        $hasAuthorSubscribeRecord->update([
                            'active' => true,
                        ]);
                        DB::commit();
                    } catch (Exception $e) {
                        $error = $e->getMessage();
                        DB::rollBack();
                        return response()->json(['error' => $error]);
                    }
                    return response()->json(['success' => 'ok', 'id' => $id]);
                }
            } else {
                DB::beginTransaction();
                try {
                    ResearchAuthorSubscriber::create([
                        'user_id'   => auth()->user()->id,
                        'author_id' => $id,
                        'active'    => true,
                        'email'     => auth()->user()->email,
                    ]);
                    DB::commit();
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    DB::rollBack();
                    return response()->json(['error' => $error]);
                }
            }
            return response()->json(['success' => 'ok', 'id' => $id]);

        }

    }



}
