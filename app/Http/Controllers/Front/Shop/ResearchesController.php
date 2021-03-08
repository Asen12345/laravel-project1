<?php

namespace App\Http\Controllers\Front\Shop;

use App\Eloquent\FeedbackShop;
use App\Eloquent\Researches;
use App\Eloquent\ResearchesAuthor;
use App\Eloquent\ShopCategory;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\Shop\ResearchesPageContent;
use Carbon\Carbon;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;

class ResearchesController extends Controller
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

    public function index(Request $request)
    {
        if (!empty($this->category)) {
            if (empty($request->url_sub_section)) {
                $researches = Researches::whereHas('category', function ($query) {
                    $query->whereIn('shop_categories.id', $this->array_child_cat_id);
                })->orWhereHas('category', function ($query) {
                    $query->where('shop_categories.id', $this->category->id);
                })
                    ->orderBy('published_at', 'DESC')
                    ->paginate(30);
            } else {
                $researches = $this->category->researches()
                    ->orderBy('published_at', 'DESC')
                    ->paginate(30);
            }

        } else {
            $researches = Researches::orderBy('published_at', 'DESC')
                ->paginate(30);
        }

        session()->put([
            'model_cat' => $this->model_cat,
            'model_cat_sub' => $this->model_cat_sub,
        ]);
        $categoryName = $this->category->name ?? null;

        return view('front.page.shop.researches-page', [
            'content_page'  => (new ResearchesPageContent())->researchesPageContent($this->model_cat, $this->model_cat_sub),
            'researches'    => $researches,
            'categoryName'  => $categoryName
        ]);
    }


    public function search(Request $request)
    {
        $researches = Researches::where('title', 'LIKE', '%' . $request->title .'%')->paginate(30);

        return view('front.page.shop.researches-page', [
            'content_page'  => (new ResearchesPageContent())->researchesPageContent($this->model_cat, $this->model_cat_sub),
            'researches'    => $researches,
            'value'         => $request->title,
        ]);
    }

    public function researchesPage(Request $request)
    {
        $research = Researches::where('id', $request->id)
            ->first();

        if(session()->get('model_cat') || session()->get('model_cat_sub')) {
            $model_cat = session()->get('model_cat');
            $model_cat_sub = session()->get('model_cat_sub');
        } else {
            if ($research->category->isNotEmpty()) {
                if (!empty($research->category[0]->parent_id == 0)) {
                    $model_cat = $research->category[0];
                    $model_cat_sub = null;
                } else {
                    $model_cat = $research->category[0]->parent;
                    $model_cat_sub = $research->category[0];
                }
            } else {
                /*У иследования нет категории -> она обязательна к заполнению в админке.*/
                return redirect()->back()->withErrors(['error' => 'Произошла ошибка.']);
            }
        }

        \ViewsCount::process($research);


        $relatedResearch = Researches::where('id', '!=', $request->id)
            ->whereBetween('published_at', [Carbon::now()->subMonths(6), Carbon::now()])
            ->inRandomOrder()
            ->get();


        return view('front.page.shop.research-page', [
            'content_page'     => (new ResearchesPageContent())->researchPageContent($model_cat, $model_cat_sub, $research),
            'research'         => $research,
            'relatedResearch'  => $relatedResearch
        ]);

    }

    public function downloadDemo(Request $request)
    {
        if (auth()->check()) {
            $researches = Researches::find($request->id);
            $pathToFile = storage_path('app/private'. $researches->demo_file);
            $researches->increment('download', 1);
            return response()->download($pathToFile);
        }
    }

    public function freeDemo(Request $request)
    {
        if (auth()->check()) {
            $researches = Researches::find($request->id);
            if ($researches->price == 0) {
                $pathToFile = storage_path('app/private'. $researches->file);
                $researches->increment('download', 1);
                return response()->download($pathToFile);
            } else {
                return abort(500);
            }
        }
    }

    public function feedbackSend(Request $request, $id)
    {
        $request['research_id'] = $id;
        FeedbackShop::create($request->all());
        return redirect()->back()
            ->with('success', 'Сообщение успешно отправлено.');
    }
}
