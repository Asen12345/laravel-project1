<?php

namespace App\Http\Controllers\Front\Posts;

use App\Repositories\Frontend\Blog\DiscussionRepository;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class DiscussionController extends Controller
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваша сессия была закрыта. Войдите в аккаунт.'
                ]);
            } else {
                $this->user = auth()->user();
                if ($this->user->active !== true) {
                    return redirect()->back()->withErrors([
                        'error' => 'Учетная запись не одобрена, ожидайте E-mail письма об активации.'
                    ]);
                }
                return $next($request);
            }
        });
    }

    public function send(Request $request, $post_id) {
        $this->validateComment($request->all());
        /*CheckBox*/
        $request['anonym']             = $this->checkboxToBoolean($request->anonym);
        $request['notify_new_comment'] = $this->checkboxToBoolean($request->notify_new_comment);

        $request['user_id']            = $request->user()->id;
        $request['post_id']            = $post_id;

        $comment = (new DiscussionRepository())->sendComment($request->except('_token'));

        return redirect()
            ->back()
            ->with('success', 'Комментарий успешно добавлен.');
    }

    public function validateComment($request) {
        $rules = ([
            'text'               => ['required', 'string'],
            'anonym'             => ['string', 'max:2'],
            'notify_new_comment' => ['string', 'max:2'],
        ]);
        $messages = [
            'text.required' => 'Текст сообщения обязателен.',
        ];

        $validator = Validator::make($request, $rules, $messages)->validate();

        return $validator;
    }
}
