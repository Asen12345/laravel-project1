<?php

namespace App\Http\Controllers\Front\UserAuth;

use App\Eloquent\DataTemplate;
use App\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Repositories\Back\UserRepository;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'company'  => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
    }

    public function showRegistrationForm()
    {
        $title   = 'Регистрация пользователя';
        $route_action = 'user.register.full';
        $text_before_form = DataTemplate::where('template_id', 'full_register')->first();

        return view('front.user_auth.register', [
            'page_content' => [
                'title' => $title,
                'route_action' => $route_action,
            ],
            'text_before_form' => $text_before_form->text
        ]);
    }

    /*public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        $user = (new UserRepository())->createUserFull($request->all());

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('success', 'Пользователь зарегистрирован, ожидайте email на указанный адрес.');
    }

    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'company'  => $data['company'],
            'password' => Hash::make($data['password']),
        ]);
    }*/

    /**
     * Get the guard to be used during registration.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
