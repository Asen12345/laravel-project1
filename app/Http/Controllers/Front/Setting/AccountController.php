<?php

namespace App\Http\Controllers\Front\Setting;

use App\Eloquent\AlertAccount;
use App\Eloquent\BlogPostDiscussion;
use App\Eloquent\BlogPostSubscriber;
use App\Eloquent\CompanyType;
use App\Eloquent\GeoCountry;
use App\Eloquent\Mailing;
use App\Eloquent\MailingUser;
use App\Eloquent\NotificationSubscriber;
use App\Eloquent\UnsubscribedUser;
use App\Eloquent\User;
use App\Eloquent\UserPrivacy;
use App\Eloquent\UserService;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\Setting\AccountPageContent;
use App\Repositories\Frontend\Company\CompanyRepository;
use App\Repositories\Frontend\Expert\ExpertRepository;
use Auth;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{

    protected $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваша сессия была закрыта. Войдите в аккаунт'
                ]);
            } else {
                $this->user = auth()->user();

                if (auth()->user()->permission == 'social') {
                    return redirect()->back();
                }

                return $next($request);
            }
        });

    }

    public function index(Request $request) {
        /*If page is Message*/
        if ($request->type === 'message') {
            return $this->pageIndex();
        }
        if ($request->type === 'blog'){
            return $this->pageBlog();
        }
        if ($request->type === 'friends'){
            return $this->pageFriends();
        }
        if ($request->type === 'comments'){
            return $this->pageComments();
        }
        if ($request->type === 'news'){
            return $this->pageNews();
        }
        if ($request->type === 'topic'){
            return $this->pageTopic();
        }
        if ($request->type === 'subscriptions'){
            return $this->pageSubscriptions();
        }
        if ($request->type === 'services'){
            return $this->pageServices();
        }
        if ($request->type === 'purchase'){
            return $this->pagePurchase();
        }
        if (empty($request->type)) {
            /*If page is main*/
            $page = 'main';
            $countries = GeoCountry::where('hidden', false)
                ->select('id', 'title', 'title_en')
                ->orderBy('position', 'asc')->get();
            $company_type = CompanyType::whereNotNull('id')
                ->orderBy('name', 'asc')->get();
        } else {
            /*If page another*/
            $countries = null;
            $company_type = null;
            $page = $request->type;
        }
        $user = User::where('id', $this->user->id)->with(['socialProfile', 'privacy', 'company', 'friends', 'friendsUsers'])->first();

        return view('front.page.setting.page.page-'. $page, [
            'user'                => $user,
            'countries'           => $countries,
            'company_type'        => $company_type,
            'content_page'        => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active' => $page
        ]);
    }

    /*Message page*/
    protected function pageIndex() {
        $user = User::where('id', $this->user->id)->first();



        /*Sorting by relationship model*/
        $messagesTreads = $user->thread()
            ->withCount(['messages as hasNotRead' => function ($query) {
                $query->whereNull('read_at')
                ->where('user_id', '!=', $this->user->id);
            }])
            ->withCount(['lastMessage as lastMessage' => function($query) {
                $query->latest()->take(1)->select('created_at');
            }])
            ->orderBy('hasNotRead', 'DESC')
            ->orderBy('lastMessage', 'DESC')
            ->paginate(30);



        foreach ($messagesTreads as $tread) {
            $companion = $tread->messageParticipants()->where('user_id', '!=', $this->user->id)->first();
            if($tread->messages()->whereNull('read_at')->get()->isNotEmpty()) {
                $tread['not_read'] = true;
            } else {
                $tread['not_read'] = false;
            }
            $tread['companion'] = $companion->user()->with('socialProfile')->first();
        }

        return view('front.page.setting.page.page-messages', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'message',
            'messagesTreads'           => $messagesTreads,
            'messages_count'           => $user->allMessages()->count(),
        ]);
    }

	/* Мой блог */
    protected function pageBlog(){
        $user = User::where('id', $this->user->id)->with(['blog', 'blogPosts'])->first();
        $blog = $user->blog;
        $posts = $user->blogPosts()->orderBy('created_at', 'DESC')->paginate(30);
        return view('front.page.setting.page.page-blog', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'blog',
            'blog'                     => $blog,
            'posts'                    => $posts
        ]);
    }

	/* Мои новости */
    protected function pageNews(){
        $user = User::where('id', $this->user->id)->first();
        $news = $user->news()->orderBy('created_at', 'DESC')->paginate(30);
        return view('front.page.setting.page.page-news', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'news',
            'news'                     => $news
        ]);
    }

    protected function pageTopic(){
        $user = User::where('id', $this->user->id)->first();
        $topics = $user->topics()->where('published', true)->paginate(1);
        return view('front.page.setting.page.page-topic', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'topic',
            'topics'                     => $topics
        ]);
    }

    protected function pageServices(){
        $user = User::where('id', $this->user->id)->first();
        $services = $user->services()->get();
        return view('front.page.setting.page.page-services', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'services',
            'services'                 => $services
        ]);
    }

    protected function pagePurchase(){

        $user = User::where('id', $this->user->id)->first();

        $purchase = $user->cart()
            ->with('purchases')
            ->withCount(['purchases as total_count' => function ($query) {
                $query->select(DB::raw('SUM(price) as total_count'));
            }])
            ->orderBy('id', 'desc')
            ->paginate(30);

        return view('front.page.setting.page.page-purchase', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'purchase',
            'purchase'                 => $purchase
        ]);
    }

    protected function pageSubscriptions(){
        $user = User::where('id', $this->user->id)->first();
        $blogs = $user->blogsSubscribe()->where('blog_post_subscribers.active', true)->paginate(30);
        return view('front.page.setting.page.page-subscription', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'subscriptions',
            'blogs'                    => $blogs
        ]);
    }

    protected function pageComments(){
        $user = User::where('id', $this->user->id)->first();
        $comments = $user->comments()
                ->with('post')
                ->orderBy('created_at', 'DESC')->paginate(30);
        return view('front.page.setting.page.page-comments', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'comments'                 => $comments,
            'menu_setting_active'      => 'comments',
        ]);
    }

    public function commentDestroy($id) {
        $comment = BlogPostDiscussion::find($id);
        if (auth()->user()->id == $comment->user_id) {
            $comment->delete();
        }
        return redirect()
            ->back()
            ->with('success', 'Комментарий успешно удален');
    }

    protected function pageFriends(){
        $user = User::where('id', $this->user->id)->with(['socialProfile', 'privacy', 'company', 'friends', 'friendsUsers'])->first();
        $friends = $user->friendsUsers()->where('accepted', true)->with(['socialProfile', 'company'])->paginate(10, ['*'], 'friends');
        $allFriends = $user->friendsUsers()->where('accepted', true)->count();
        $friends_request = $user->requestFriendsUsers()->where('accepted', false)->with(['socialProfile', 'company'])->paginate(10, ['*'], 'request');
        $allRequest = $user->requestFriendsUsers()->where('accepted', false)->count();

        return view('front.page.setting.page.page-friends', [
            'user'                => $user,
            'friends'             => $friends,
            'friends_request'     => $friends_request,
            'content_page'        => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active' => 'friends',
            'allFriends'          => $allFriends,
            'allRequest'          => $allRequest
        ]);
    }

    /*Rout*/
    public function updateAccount(Request $request) {
        $page = $request->type . 'Update';
        return $this->$page($request);
    }

    /*Ajax unsubscribe blog*/
    public function subscriptionsBlogUpdate(Request $request)
    {
        $user = auth()->user();
        if ($request->type === 'unsubscribe') {
            $id = $request->val;
            $subscribeRecord = BlogPostSubscriber::where('user_id', auth()->user()->id)
                ->where('blog_id', $id)
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
            $hasBlogSubscribeRecord = BlogPostSubscriber::where('user_id', auth()->user()->id)
                ->where('blog_id', $id)->first();

            if (!empty($hasBlogSubscribeRecord)){
                if($hasBlogSubscribeRecord->active == true) {

                    return response()->json(['error' => 'Вы уже подписаны на этот блог.']);

                } else {

                    DB::beginTransaction();
                    try {
                        $hasBlogSubscribeRecord->update([
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
                    BlogPostSubscriber::create([
                        'user_id' => auth()->user()->id,
                        'blog_id' => $id,
                        'active' => true,
                        'email' => auth()->user()->email,
                    ]);
                    DB::commit();
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    DB::rollBack();
                    return response()->json(['error' => $error]);
                }
            }


            return response()->json(['success' => 'ok', 'id' => $id]);

        } elseif ($request->type === 'notifications_subscribed' || $request->type === 'invitations') {
            /*Новостная рассылка*/
            /*Уведомления и приглашения */
            DB::beginTransaction();
            try {
                if ($request->type === 'notification_subscribed') {
                    if ($request->val == false) {
                        NotificationSubscriber::where('email', $user->email)->delete();
                        UnsubscribedUser::create(['email' => $user->email]);
                    } else {
                        NotificationSubscriber::create(['email' => $user->email]);
                        UnsubscribedUser::where('email', $user->email)->delete();
                    }
                }
                $user->update([
                    $request->type => $request->val
                ]);
                DB::commit();
            } catch (Exception $e) {
                $error = $e->getMessage();
                DB::rollBack();
                return response()->json(['error' => $error]);
            }

            return response()->json(['success' => 'ok', 'id' => $request->val]);

        }

    }

    /**
     * @param $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    protected function mainUpdate($request) {
        $data = $this->validateUpdatePassword($request);

        if (auth()->user()->permission == 'expert'){
            $req = (new ExpertRepository())->updateUserExpert(Arr::add($data, 'user_id', $this->user->id));
        } elseif (auth()->user()->permission == 'company'){
            $req = (new CompanyRepository())->updateUserCompany(Arr::add($data, 'user_id', $this->user->id));
        }

        if (!empty($req['error'])){
            return redirect()->back()
                ->withErrors(['error' => $req['error']]);
        }
        if ($req['change_pass'] === true) {
            return redirect()
                ->route('user.login.logout');
        } else {
            return redirect()
                ->route('front.setting.account')
                ->with('success', 'Данные успешно изменены');
        }

    }

    protected function privacyUpdate($request) {
        $user_privacy = UserPrivacy::firstOrCreate([
            'user_id' => $this->user->id
        ], [
            $request->input('privacy')
        ]);
        $user_privacy->update($request->input('privacy'));

        return redirect()
            ->route('front.setting.account', ['page' => 'privacy'])
            ->with('success', 'Данные успешно изменены');
    }

    protected function servicesUpdate($request) {

        UserService::where('user_id', $this->user->id)->delete();

        if (!empty($request->content)) {
            foreach ($request->content as $row) {
                UserService::create(Arr::add($row, 'user_id', $this->user->id));
            }
        }

        return redirect()
            ->route('front.setting.account', ['page' => 'services'])
            ->with('success', 'Данные успешно изменены');
    }

    /**
     * @param $request
     * @return array
     * @throws ValidationException
     */
    protected function validateUpdatePassword($request) {
        /*
         * If input password is empty or null -> deleted this input, because send in observer (null)
         * And (null) is not valid for column in db.
         *
         * */
        if (empty($request->input('password'))) {
            $data = $request->except('password', 'password_confirmation', '_token');
        } else {
            $rules = ([
                'password' => ['required', 'string', 'confirmed'],
            ]);
            $messages = [
                'confirmed'    => 'Пароли не совпадают',
            ];
            Validator::make($request->all(), $rules, $messages)->validate();
            if ($request->input('password') !== $request->input('password_confirmation')) {
                return redirect()->back()
                    ->withErrors([
                        'email' => 'Введенные пароли не совпадают'
                    ]);
            }
            $data = $request->except('_token');

        }

        return $data;

    }

}
