<?php

namespace App\Http\Controllers\AdminPanel\Newsletter;

use App\Eloquent\NotificationSubscriber;
use App\Eloquent\UnsubscribedUser;
use App\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\PageContent\AdminPanel\Newsletter\NewsletterPageContent;
use App\Rules\EmailRule;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SubscriberController extends Controller
{

    /**
     * @param Request $request
     * @return Factory|View
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        if ($request->has('email')) {
            $this->validate($request, [
                'email'     => 'string',
            ]);
            $users = NotificationSubscriber::where('email', 'LIKE', '%' . $request->input('email') . '%')
                ->select('id', 'email');
        } else {
            $users = NotificationSubscriber::whereNotNull('id')->select('id', 'email');
        }

        $content = (new NewsletterPageContent())->indexSubscriberPageContent();

        return view('admin_panel.newsletter.subscriber.index', [
            'content' => $content,
            'users'   => $users->paginate('30'),
            'filter'  => $request->input('email') ?? null,
            'count'   => $users->count(),
        ]);

    }

    /**
     * @param $email
     * @return RedirectResponse
     * @throws Exception
     */
    public function unsubscribe($email)
    {
        DB::beginTransaction();
        try {
            /*
             * If user has account -> changing the status -> in the observer, a subscriber and a unsubscribe
             * If don't have account -> subscribe delete and create unsubscribe
             * */
            $user = User::where('email', $email)->first();
            if (!empty($user)) {
                User::where('email', $email)->first()->update([
                    'notifications_subscribed' => false
                ]);
            } else {
                NotificationSubscriber::where('email', $email)->delete();
                UnsubscribedUser::create(['email' => $email]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()
            ->back()
            ->with('success', 'Пользователь успешно отписан.');

    }
    public function create()
    {
        $content = (new NewsletterPageContent())->createSubscriberPageContent();
        return view('admin_panel.newsletter.subscriber.create', [
            'content' => $content
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|RedirectResponse|View
     * @throws ValidationException
     */
    public function subscribe(Request $request)
    {
        $this->validatorMail($request);
        $subscribeUser = NotificationSubscriber::where('email', $request->input('email'))->first();
        $unsubscribeUser = UnsubscribedUser::where('email', $request->input('email'))->first();

        if (!is_null($unsubscribeUser)) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Пользователь c таким email был отписан от рассылки.'
                ]);
        }
        if (!empty($subscribeUser)) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Пользователь c таким email уже подписан.'
                ]);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!empty($user)) {
            /*
             * If user has account in site -> update
             * After update -> in the observer, a subscriber and a unsubscribe
             *
             * */
            $user->update([
                'notifications_subscribed' => true
            ]);

        } else {
            /*If user don't have account -> only create subscribe email*/
            NotificationSubscriber::create([
                'email'  => $request->input('email')
            ]);
        }

        return redirect()
            ->route('admin.newsletter.subscribers.index')
            ->with('success', 'Пользователь успешно подисан.');


    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function validatorMail (Request $request)
    {
        $rules = ([
            'email' => new EmailRule(),
        ]);

        Validator::make($request->all(), $rules)->validate();

    }

}
