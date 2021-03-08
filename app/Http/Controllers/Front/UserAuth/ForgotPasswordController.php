<?php

namespace App\Http\Controllers\Front\UserAuth;

use App\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Auth\RegenerateNewPassword;
use App\Http\Requests\Front\Auth\ResetLinkEmail;
use App\Jobs\NewUserPasswordJob;
use App\Jobs\SendForgotPasswordJob;
use App\Repositories\Back\UserRepository;
use DB;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * @param ResetLinkEmail $request
     * @return RedirectResponse
     */
    public function sendResetLinkEmail(ResetLinkEmail $request)
    {
        $email = $request->input('email');

        $user = User::whereEmail($email)->first();

        $hash = app("auth.password.broker")->createToken($user);

        SendForgotPasswordJob::dispatch($user, $hash);

        return redirect()
            ->back()
            ->with('success', 'Письмо со ссылкой для сброса пароля отправлено');

    }

    /**
     * Make random password, store it to user and send via email
     *
     * @param RegenerateNewPassword $request
     * @return RedirectResponse|void
     */
    public function regenerateNewPassword(RegenerateNewPassword $request)
    {
        $user = User::whereEmail($request->input('email'))->first();
        $hash = $request->input('hash');

        // incorrect email or hash
        if (!app("auth.password.broker")->tokenExists($user, $hash)) {
            return redirect()->route('front.home')
                ->with('success', 'Неверная ссылка восстановления пароля');
        }

        $password = Str::random(6);
        $user->updatePassword($password, Hash::make($password));
        app("auth.password.broker")->deleteToken($user);

        NewUserPasswordJob::dispatch($user, 'password_change_user');

        return redirect()->route('front.home')
            ->with('success', 'Новый пароль отправлен вам на почту')->send();
    }
}
