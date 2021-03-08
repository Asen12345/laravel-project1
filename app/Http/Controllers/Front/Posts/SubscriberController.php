<?php

namespace App\Http\Controllers\Front\Posts;

use App\Eloquent\BlogPostSubscriber;
use App\Rules\EmailRule;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class SubscriberController extends Controller
{
    public function __construct()
    {

    }

    public function subscribe(Request $request, $id)
    {
        $rules = ([
            'email' => new EmailRule(),
        ]);
        $messages = [
            'email.required' => 'Поле E-mail обязательно для заполнения.',
            'email'          => 'Некорректный E-mail.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $sub = BlogPostSubscriber::where('blog_id', $id)
            ->where('email', $request->email)->first();
        if (empty($sub)) {
            DB::beginTransaction();
            try {
                BlogPostSubscriber::create([
                    'user_id' => null,
                    'blog_id' => $id,
                    'active' => true,
                    'email' => $request->email
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $id_index = $e->getMessage();
                return redirect()->back()->withErrors(['error' => $id_index]);
            }
            return redirect()->back()->with('success', 'Вы успешно подписались. Спасибо.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Вы уже подписаны на этот блог.']);
        }

    }
}
