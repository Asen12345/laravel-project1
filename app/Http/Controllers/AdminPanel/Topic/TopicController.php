<?php

namespace App\Http\Controllers\AdminPanel\Topic;

use App\Eloquent\Topic;
use App\Eloquent\TopicSubscriber;
use App\Eloquent\User;
use App\Http\PageContent\AdminPanel\Topic\TopicPageContent;
use App\Repositories\Back\Topic\TopicRepository;
use Arr;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class TopicController extends Controller
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

        $content = (new TopicPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $topics = (new TopicRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->orderBy($sortBy, $sortOrder)
            ->withCount('answers')
            ->paginate('50');
        $filter_data = $request->except('_token');

        return view('admin_panel.topic.topic.index', [
            'content'     => $content,
            'topics'     => $topics,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    public function create() {
        $content = (new TopicPageContent())->editAndCreateContent();
        return view('admin_panel.topic.topic.create', [
            'content'     => $content,

        ]);
    }

    public function store(Request $request) {
        $this->validateForm($request);
        $request['main_topic'] = $this->checkboxToBoolean($request->main_topic);
        $request['published'] = $this->checkboxToBoolean($request->published);
        DB::beginTransaction();
        try {
            if ($request->main_topic == true) {
                $old_main = (new TopicRepository())->where('main_topic', true)->get();
                foreach($old_main as $record){
                    $record->update([
                        'main_topic' => false,
                    ]);
                }
            }
            $topic = (new TopicRepository())->create($request->all());
            if (!empty($request->user)) {
                foreach ($request->user as $user) {
                    TopicSubscriber::create([
                        'topic_id' => $topic->id,
                        'user_id' => $user,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()->withErrors(['error' => $id_index]);
        }
        return redirect()->route('admin.topic.index')
            ->with('success', 'Запись успешно создана');
    }

    public function edit($id) {
        $content = (new TopicPageContent())->editAndCreateContent();
        $topic   = (new TopicRepository())->getById($id);
        return view('admin_panel.topic.topic.edit', [
            'content' => $content,
            'topic'   => $topic
        ]);
    }

    public function update(Request $request, $id){
        $this->validateForm($request);
        $request['main_topic'] = $this->checkboxToBoolean($request->main_topic);
        $request['published'] = $this->checkboxToBoolean($request->published);
        DB::beginTransaction();
        try {
            if ($request->main_topic == true) {
                $old_main = Topic::where('id', '!=', $id)
                    ->where('main_topic', true)
                    ->get();
                foreach($old_main as $record){
                    if ($record->main_topic == true) {
                        $record->update([
                            'main_topic' => false,
                        ]);
                    }
                }
            }
            $topic = Topic::find($id);
            $topic->update($request->all());
           // (new TopicRepository())->updateById($id, $request->all());

            $hasUserInDb = Arr::flatten(TopicSubscriber::where('topic_id', $id)->select('user_id')->get()->toArray());

            if (!empty($request->user)){
                foreach ($request->user as $user) {
                    if (!in_array($user, $hasUserInDb)) {
                        TopicSubscriber::create([
                            'topic_id' => $id,
                            'user_id' => $user,
                        ]);
                    }
                }
                foreach ($hasUserInDb as $item) {
                    if (!in_array($item, $request->user)) {
                        TopicSubscriber::where('topic_id', $id)
                            ->where('user_id', $item)
                            ->delete();
                    }
                }
            } else {
                TopicSubscriber::where('topic_id', $id)
                    ->delete();
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()->withErrors(['error' => $id_index]);
        }
        return redirect()->route('admin.topic.index')
            ->with('success', 'Запись успешно обновлена');

    }

    /**
     * @param $id int
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id){
        DB::beginTransaction();
        try {
            $topic = (new TopicRepository())->getById($id);
            $topic->recordsSubscribers()->delete();
            $topic->answers()->delete();
            $topic->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()->withErrors(['error' => $id_index]);
        }

        return redirect()->back()
            ->with('success', 'Запись успешно удалена');
    }

    public function checkBox(Request $request) {
        DB::beginTransaction();
        try {
            $old_main = (new TopicRepository())->where('main_topic', true)->get();
            foreach($old_main as $record){
                $record->update([
                    'main_topic' => false,
                ]);
            }
            (new TopicRepository())->getById($request->id)
                ->update([
                    'main_topic' => $request->active,
                ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return response()->json(array('success' => 'false', 'error' => $id_index));
        }

        return response()->json(array('success' => 'ok', 'mess' => 'Запись успешно обновлена.'));

    }



    public function autocomplete(Request $request) {

        $input = $request->input('name');

        $data = User::where('permission', 'expert')->where('name', 'LIKE', '%' . $input . '%')
            ->take(5)
            ->select('id', 'name')
            ->get();
        /*if ($data->isEmpty()) {
            $data_null[] = [
                'id'   => $input,
                'name' => $input
            ];
            return response()->json((object)$data_null);
        }*/

        return response()->json($data);
    }


    public function validateForm ($request) {

        $rules = ([
            'title'            => ['required', 'string', 'max:255'],
            'url_ru'           => ['required', 'string', 'max:255'],
            'url_en'           => ['required', 'string', 'max:255'],
            'text'             => ['required', 'string'],
            'published_at'     => ['required', 'string', 'max:255'],
        ]);
        $messages = [
            'required' => 'Поле :attribute обязателен к заполнению.',
        ];
        $names = [
            'title'            => "'Заголовок'",
            'url_ru'           => "'Url ru'",
            'url_en'           => "'Url en'",
            'text'             => "'Полный текст темы'",
            'published_at'     => "'Дата публикации темы'",
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $names)->validate();

        return $validator;

    }
}
