<?php

namespace App\Http\Controllers\Front\Feedback;

use App\Eloquent\Feedback;
use App\Eloquent\FeedbackSubject;
use App\Eloquent\User;
use App\Http\PageContent\Frontend\Feedback\FeedbackPageContent;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class FeedbackController extends Controller
{
    public function index()
    {
        $subjects = FeedbackSubject::all();
        return view('front.page.feedback.feedback', [
            'subjects'     => $subjects,
            'content_page' => (new FeedbackPageContent())->feedbackPageContent()
        ]);
    }

    public function send(Request $request)
    {
        if (!auth()->check()){
            $rules = ([
                'subject'    => ['required', 'string', 'max:255'],
                'text'       => ['required', 'string'],
                'email'      => ['required', 'email'],
                'g-recaptcha-response' => ['required', 'captcha']
            ]);
            $messages = [
                'subject.required'      => 'Тема обязателена к заполнению.',
                'text.required'         => 'Текст сообщения обязателен.',
            ];
            Validator::make($request->all(), $rules, $messages)->validate();

            Feedback::create($request->all());

            return redirect()->back()->with('success', 'Сообщение отправлено.');
        } else {
            $user = User::where('id', auth()->user()->id)->with('socialProfile')->first();
            Feedback::create([
                'user_id'     => $user->id,
                'email'       => $user->email,
                'last_name'   => $user->socialProfile->last_name ?? 'нет',
                'first_name'  => $user->socialProfile->first_name ?? 'нет',
                'company'     => $user->company->name ?? 'нет',
                'phone'       => $user->socialProfile->work_phone ?? $user->socialProfile->mobile_phone ?? 'нет',
                'subject'     => $request->subject,
                'text'        => $request->text,
            ]);
            return redirect()->back()->with('success', 'Сообщение отправлено.');
        }
    }
}
