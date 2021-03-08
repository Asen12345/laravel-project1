<?php

namespace App\Http\Controllers\Front\Newsletter;

use App\Eloquent\BlogPostSubscriber;
use App\Eloquent\NotificationSubscriber;
use App\Eloquent\UnsubscribedUser;
use App\Rules\EmailRule;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class NewsletterController extends Controller
{

    public function subscribe(Request $request)
    {
        $rules = ([
            'email' => new EmailRule(),
        ]);
        $messages = [
            'email.required' => 'Поле Email обязательно для заполнения.',
            'email' => 'Не корректный Email',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $sub = NotificationSubscriber::where('email', $request->email)->first();
        $unsub = UnsubscribedUser::where('email', $request->email)->first();
        if (empty($sub) && empty($unsub)) {
            DB::beginTransaction();
            try {
                NotificationSubscriber::create([
                    'email' => $request->email
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $id_index = $e->getMessage();
                return redirect()->back()->withErrors(['error' => $id_index]);
            }
            return redirect()->back()->with('success', 'Вы успешно подписались на новостную рассылку. Спасибо.');
        } else {
            if (!empty($sub)) {
                return redirect()->back()->withErrors(['error' => 'Данный email уже подписан на новостную рассылку.']);
            } elseif(!empty($unsub)) {
                return redirect()->back()->withErrors(['error' => 'Данный email уже был отписан.']);
            }
        }
    }

}
