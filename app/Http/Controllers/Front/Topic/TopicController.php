<?php

namespace App\Http\Controllers\Front\Topic;

use App\Eloquent\Topic;
use App\Eloquent\TopicAnswer;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\Topic\TopicPageContent;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Validator;

class TopicController extends Controller
{
    public function index() {

        $topics = Topic::where('published', true)
            ->with(['answers' => function ($query){
                $query->where('published', true)
                ->orderBy('created_at', 'DESC');
            }])
            ->orderBy('published_at', 'desc')
            ->paginate(30);

        return view('front.page.topic.topic', [
            'content_page'  => (new TopicPageContent())->indexContent(),
            'topics'        => $topics,
        ]);
    }

    public function topicPage($url_en) {

        $topic = Topic::where('url_en', $url_en)
            ->where('published', true)
            ->first();

        $topic['answers'] = $topic->answers()
            ->where('published', true)
            ->with(['socialProfileUser', 'user'])
            ->orderBy('created_at', 'DESC')
            ->paginate(30);

        if (auth()->check()) {
            $answerer = !$topic->answers()->where('user_id', auth()->user()->id)->get()->isEmpty();
        } else {
            $answerer = null;
        }

        return view('front.page.topic.topic-page', [
            'content_page'  => (new TopicPageContent())->pageContent($topic),
            'topic'         => $topic,
            'answerer'      => $answerer,
            'userAnswer'    => null
        ]);
    }
    public function editAnswer(Request $request, $url_en)
    {
        $topic = Topic::where('url_en', $url_en)
            ->where('published', true)
            ->first();

        $topic['answers'] = $topic->answers()
            ->where('published', true)
            ->with(['socialProfileUser', 'user'])
            ->orderBy('published_at')
            ->paginate(30);

        /*user answer*/
        $userAnswer = $topic->answers()
            ->where('published', true)
            ->where('user_id', auth()->user()->id)
            ->first();


        if (auth()->check() && !$topic->answers()->where('user_id', auth()->user()->id)->get()->isEmpty()) {
            $answerer = 'edit';
        } else {
            $answerer = null;
        }

        return view('front.page.topic.topic-page', [
            'content_page'  => (new TopicPageContent())->pageContent($topic),
            'topic'         => $topic,
            'answerer'      => $answerer,
            'userAnswer'    => $userAnswer
        ]);
    }

    public function sendAnswer(Request $request) {
        $rules = ([
            'text'         => ['required', 'string'],
            'topic_id'     => ['required', 'string', 'max:255'],
        ]);
        $messages = [
            'required' => 'Поле :attribute обязателен к заполнению.',
        ];
        $names = [
            'text'         => "'Полный текст темы'",
            'topic_id'     => "'ID темы'",
        ];
        Validator::make($request->all(), $rules, $messages, $names)->validate();

        $topic = Topic::where('id', $request->topic_id)->first();
        $user = auth()->user();
        if ($topic->subscriber->contains('id', $user->id)){
            DB::beginTransaction();
            try {
                TopicAnswer::create([
                    'topic_id'     => $topic->id,
                    'user_id'      => $user->id,
                    'published'    => false,
                    'published_at' => Carbon::now(),
                    'text'         => $request->text,
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $id_index = $e->getMessage();
                return redirect()->back()->withErrors(['error' => 'При отправке произошла ошибка.']);
            }
            return redirect()->back()
                ->with('success', 'Ответ успешно добавлен.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Вы не подписанны на данную тему.']);
        }
    }

    public function updateAnswer(Request $request) {
        $rules = ([
            'text'         => ['required', 'string'],
            'topic_id'     => ['required', 'string', 'max:255'],
        ]);
        $messages = [
            'required' => 'Поле :attribute обязателен к заполнению.',
        ];
        $names = [
            'text'         => "'Полный текст темы'",
            'topic_id'     => "'ID темы'",
        ];
        Validator::make($request->all(), $rules, $messages, $names)->validate();

        $topic = Topic::where('id', $request->topic_id)->first();
        $user = auth()->user();

        if ($topic->subscriber->contains('id', $user->id)){
            $answer = TopicAnswer::where('topic_id', $topic->id)
                ->where('user_id', $user->id)
                ->first();
            DB::beginTransaction();
            try {
                $answer->update(['text' => $request->text]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $id_index = $e->getMessage();
                return redirect()->back()->withErrors(['error' => 'При отправке произошла ошибка.']);
            }
            return redirect()
                ->route('front.page.topic.page', ['url_en' => $topic->url_en])
                ->with('success', 'Ответ успешно обновлен.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Вы не подписанны на данную тему.']);
        }
    }
}
