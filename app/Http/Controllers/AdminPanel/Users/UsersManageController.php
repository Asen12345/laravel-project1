<?php

namespace App\Http\Controllers\AdminPanel\Users;

use App\Eloquent\BlogPostDiscussion;
use App\Eloquent\BlogPostSubscriber;
use App\Eloquent\BlogPostTags;
use App\Eloquent\CompanyType;
use App\Eloquent\Friends;
use App\Eloquent\GeoCountry;
use App\Eloquent\MailingUser;
use App\Eloquent\Message;
use App\Eloquent\MessageParticipant;
use App\Eloquent\NewsIdNewsSceneId;
use App\Eloquent\NotificationSubscriber;
use App\Eloquent\User;
use App\Eloquent\UserNotifyComment;
use App\Eloquent\UserPrivacy;
use App\Eloquent\UserService;
use App\Http\PageContent\AdminPanel\Users\UsersPageContent;
use App\Jobs\RegisterNewUserJob;
use App\Repositories\Back\UserRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UsersManageController extends Controller
{
    public function index(Request $request)
    {
        $parameters = $request->all();

        $this->validate($request, [
            'name'                     => 'string|max:40|nullable',
            'active'                   => 'boolean|nullable',
            'notifications_subscribed' => 'boolean|nullable',
            'role'                     => 'string|max:8|nullable',
        ]);

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

        $content = (new UsersPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $users = (new UserRepository())
            ->where('permission', '!=', 'social');

        if (count($parameters) > 0) {
            $users = $users->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->orderBy($sortBy, $sortOrder)->paginate(50);
            $users->appends($parameters);
        } else {
            $users = $users->orderBy($sortBy, $sortOrder)->paginate(50);
        }

        return view('admin_panel.users.index', [
            'content'     => $content,
            'users'       => $users,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $content = (new UsersPageContent())->editAndCreateUserPageContent();
        $company_type = CompanyType::all();
        $countries = GeoCountry::where('hidden', false)
            ->select('id', 'title', 'title_en')
            ->orderBy('position', 'asc')->get();
        //$permissions = Permission::where('role_name', 'redactor')->get();

        return view('admin_panel.users.create', [
            'content'        => $content,
            'countries'      => $countries,
            'user'           => null,
            'company_type'   => $company_type,
            'social_profile' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validateUpdatePassword($request);

        if ($request->input('permission') == 'expert') {
            $user = (new UserRepository())->createUserExpert($request->all());
        } elseif ($request->input('permission') == 'company') {
            $user = (new UserRepository())->createUserCompany($request->all());
        }

        /*if (!empty($user)) {
            RegisterNewUserJob::dispatch($user, 'send_user');
        }*/
        return redirect()
            ->route('admin.users.edit', ['id' => $user->id])
            ->with('success', 'Пользователь успешно добавлен.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request) {

        $content = (new UsersPageContent())->editAndCreateUserPageContent();
        $company_type = CompanyType::all();
        $countries = GeoCountry::where('hidden', false)
            ->select('id', 'title', 'title_en')
            ->orderBy('position', 'asc')->get();
        $user = (new UserRepository())->getById($id);

        return view('admin_panel.users.edit', [
            'content'        => $content,
            'countries'      => $countries,
            'user'           => $user,
            'social_profile' => $user->socialProfile,
            'userServices'   => $user->services,
            'company_type'   => $company_type
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $data = $this->validateUpdatePassword($request);

        /*If request is has update privacy*/
        if (!empty($request->input('privacy'))) {
            $this->updatePrivacy($request, $id);
        }

        if (!empty($request->input('services'))) {
            $this->updateServices($request, $id);
        }

        if ($request->input('permission') == 'expert') {
            (new UserRepository())->updateUserExpert(Arr::add($data, 'user_id', $id));
        } elseif ($request->input('permission') == 'company') {
            (new UserRepository())->updateUserCompany(Arr::add($data, 'user_id', $id));
        }

        if ($request->input('send_notification') == true && $request->input('permission') == 'company') {
            $user = User::find($id);
            RegisterNewUserJob::dispatch($user, 'send_user');
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Успешно сохранено.');
    }

    public function updatePrivacy(Request $request, $id)
    {
        $user_privacy  = UserPrivacy::firstOrCreate([
            'user_id' => $id
        ], [
            $request->input('privacy')
        ]);
        $user_privacy->update($request->input('privacy'));

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Успешно сохранено.');
    }

    public function updateServices($request, $id)
    {
        UserService::where('user_id', $id)->delete();
        if (!empty($request->services)) {
            foreach ($request->services as $row) {
                UserService::create(Arr::add($row, 'user_id', $id));
            }
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Успешно сохранено.');
    }

    /**
     * @param Request $request
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

            $data = $request->except('password', 'password_confirmation');

        } else {

            $rules = ([
                'password' => ['required', 'string', 'confirmed'],
            ]);
            $messages = [
                'confirmed'    => 'Пароли не совпадают.',
            ];

            Validator::make($request->all(), $rules, $messages)->validate();

            if ($request->input('password') !== $request->input('password_confirmation')) {
                return redirect()->back()
                    ->withErrors([
                        'email' => 'Введенные пароли не совпадают!'
                    ]);
            }

            $data = $request->all();

        }

        return $data;

    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $user = User::where('id', $id)->first();
            $posts = $user->blogPosts;
            foreach ($posts as $post) {
                $post->votes()->delete();
                $post->comments()->delete();
                $post->subscribers()->delete();
                BlogPostTags::where('blog_post_id', $post->id)->delete();
                UserNotifyComment::where('blog_post_id', $post->id)->delete();
                $post->delete();
            }
            Friends::where('user_id', $id)->orWhere('friend_id', $id)->delete();
            Message::where('user_id', $id)->delete();
            MessageParticipant::where('user_id', $id)->delete();
            NotificationSubscriber::where('email', $user->email)->delete();
            MailingUser::where('user_id', $id)->delete();
            $user->topicAnswers()->delete();
            $user->topicSubscribers()->delete();
            $user->notifyCommentsRecord()->delete();
            $user->privacy()->delete();
            $user->socialProfile()->delete();
            $user->blog()->delete();
            $news = $user->news;
            foreach ($news as $row) {
                NewsIdNewsSceneId::where('news_id', $row->id)->delete();
            }
            if ($news->isNotEmpty()){
                $user->news()->delete();
            }
            $user->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => 'Произошла ошибка при удалении: ' . $id_index]);
        }
        return redirect()->back()->with('success', 'Запись удачно удалена');
    }
}
