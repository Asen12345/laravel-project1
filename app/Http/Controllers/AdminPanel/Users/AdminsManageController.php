<?php

namespace App\Http\Controllers\AdminPanel\Users;

use App\Eloquent\Admin;
use App\Eloquent\NewsCategory;
use App\Eloquent\Permission;
use App\Http\PageContent\AdminPanel\Users\AdminsPageContent;
use App\Repositories\Back\AdminCategoryPermissionRepository;
use App\Repositories\Back\AdminRepository;
use App\Repositories\Back\AdminHasPermissionRepository;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminsManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
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

        $content = (new AdminsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $users = (new AdminRepository());

        if (count($parameters) > 0) {
            $users = $users->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->orderBy($sortBy, $sortOrder)->paginate(50);
            $users->appends($parameters);
        } else {
            $users = $users->orderBy($sortBy, $sortOrder)->paginate(50);
        }

        return view('admin_panel.admins.index', [
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
        $content = (new AdminsPageContent())->editAndCreateUserPageContent();
        $permissions = Permission::where('role_name', 'redactor')->get();
        $newsCategories = NewsCategory::all();

        return view('admin_panel.admins.create', [
            'content'        => $content,
            'user'           => null,
            'permissions'    => $permissions,
            'newsCategories' => $newsCategories,
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

        $rules = ([
            'email' => ['required', new EmailRule()],
        ]);
        $messages = [
            'email.*' => 'Ошибка при вводе email',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        if ($request->input('role') == 'admin') {
            $user = (new AdminRepository())->createUserAdmin($request->all());
        } elseif ($request->input('role') == 'redactor') {
            $user = (new AdminRepository())->createUserRedactor($request->all());
            (new AdminHasPermissionRepository())->updatePermissions($user->id, $request->permissions);
            (new AdminCategoryPermissionRepository())->updateCategoryPermissions($user->id, $request->categories);

        }

        /*if (!empty($user)) {
            RegisterNewUserJob::dispatch($user, 'send_user');
        }*/
        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Пользователь успешно добавлен.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, $id) {

        if (auth()->user()->role == 'redactor' && auth()->user()->id == $id) {
            return redirect()
                ->back()
                ->withErrors(['Вы не можете редактировать свой аккаунт.']);
        }
        $content = (new AdminsPageContent())->editAndCreateUserPageContent();
        $permissions = Permission::where('role_name', 'redactor')->get();
        $user = (new AdminRepository())->with('categoryPermission', 'permissions')->getById($id);
        $newsCategories = NewsCategory::all();

        return view('admin_panel.admins.edit', [
            'content'        => $content,
            'user'           => $user,
            'date_from'      => $request->input('date_from'),
            'date_to'        => $request->input('date_to'),
            'permissions'    => $permissions,
            'newsCategories' => $newsCategories
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

        $rules = ([
            'email' => ['required', new EmailRule()],
        ]);
        $messages = [
            'email.*' => 'Ошибка при вводе email',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        if (empty($request->active)) {
            $active = false;
        } else {
            $active = true;
        }
        $data = Arr::add($data,  'active', $active);

        if (empty($data['password'])) {
            Admin::where('id', $id)
                ->update([
                    'active'   => $data['active'],
                    'email'    => $data['email'],
                    'name'     => $data['name'],
                    'role'     => $data['role'],
                ]);
        } else {
            Admin::where('id', $id)
                ->update([
                    'active'   => $data['active'],
                    'email'    => $data['email'],
                    'name'     => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role'     => $data['role'],
                ]);
        }

        if ($request->input('role') == 'redactor') {
            (new AdminHasPermissionRepository())->updatePermissions($id, $request->permissions);
            (new AdminCategoryPermissionRepository())->updateCategoryPermissions($id, $request->categories);
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Успешно сохранено.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        (new AdminRepository())->deleteById($id);

        return redirect()
            ->back()
            ->with('success', 'Пользователь успешно удален.');

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

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    protected function validateCreatePassword ($request) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
        $messages = [
            'password.required' => 'Пароль обязателен к заполнению.',
            'confirmed'         => 'Пароли не совпадают.',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        return $request->all();

    }
}
