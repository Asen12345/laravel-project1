<?php

namespace App\Http\Controllers\AdminPanel\Topic;

use App\Eloquent\BlogPostDiscussion;
use App\Eloquent\Topic;
use App\Eloquent\TopicAnswer;
use App\Eloquent\User;
use App\Http\PageContent\AdminPanel\Topic\AnswerPageContent;
use App\Http\PageContent\AdminPanel\Comment\CommentsPageContent;
use App\Http\PageContent\AdminPanel\Topic\TopicPageContent;
use App\Repositories\Back\CommentRepository;
use App\Repositories\Back\Topic\AnswerRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AnswerController extends Controller
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
        if ($sortBy == 'title') {
            $sortBy = 'topic_count';
        }
        if ($sortBy == 'user') {
            $sortBy = 'user_count';
        }

        $content = (new AnswerPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'), $request->id);

        $answers = (new AnswerRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'id'))
            ->where(function ($query) use ($request) {
                if (!empty($request->id)){
                    $query->where('topic_id', $request->id);
                } else {
                    $query->whereNotNull('topic_id');
                }
            })
            ->with(['topic', 'user'])
            ->withCount(['topic' => function($query){
                $query->select('title');
            }])
            ->withCount(['user' => function($query){
                $query->select('name');
            }])
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.topic.answer.index', [
            'content'     => $content,
            'answers'     => $answers,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder,
        ]);
    }

    public function create() {
        $content = (new AnswerPageContent())->editAndCreateContent();
        $topics = Topic::all();
        return view('admin_panel.topic.answer.create', [
            'content'   => $content,
            'topic'     => $topics
        ]);
    }

    public function store(Request $request) {
        $rules = ([
            'text' => ['required'],
        ]);
        $messages = [
            'text.required'    => 'Текст обязателен.',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();

        $answer = TopicAnswer::where('user_id', $request->user_id)
            ->where('topic_id', $request->topic_id)->first();

        if (empty($answer)) {
            $request['published'] = $this->checkboxToBoolean($request->published);
            (new AnswerRepository())->create($request->only('topic_id', 'user_id', 'published', 'text', 'text', 'published_at'));
            return redirect()->route('admin.answer.index')
                ->with('success', 'Запись успешно создана.');
        } else {
            return redirect()->back()->withErrors([
                'error' => 'У пользователя "'. $answer->user->name .'" уже есть ответ в теме "' . $answer->topic->title . '"'
            ]);
        }



    }

    public function edit($id){

        $answer = TopicAnswer::where('id', $id)
            ->with('topic')
            ->first();
        $content = (new AnswerPageContent())->editAndCreateContent();

        return view('admin_panel.topic.answer.edit', [
            'content'  => $content,
            'answer'   => $answer,
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

        DB::beginTransaction();
        try {
            (new AnswerRepository())->updateById($id, $request->only('topic_id', 'user_id', 'published', 'text', 'text', 'published_at'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return redirect()->route('admin.answer.index')
            ->with('success', 'Запись успешно обновлена.');

    }

    public function destroy($id) {
        DB::beginTransaction();
        try{
            (new AnswerRepository())->deleteById($id);
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
        $data = Topic::where('title', 'LIKE', '%' . $input . '%')->take(5)->get();

        return response()->json($data);
    }

    public function userAutocomplete(Request $request) {
        $input = $request->input('name');
        $id    = $request->input('id');
        $topic = Topic::where('id', $id)->first();
        $data  = $topic->subscriber()->where('name', 'LIKE', '%' . $input . '%')->get();

        return response()->json($data);
    }


    public function checkBox(Request $request){

        DB::beginTransaction();
        try {
            if ($request->active !== null) {
                (new AnswerRepository())->getById($request->id)
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
