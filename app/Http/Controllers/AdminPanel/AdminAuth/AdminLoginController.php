<?php

namespace App\Http\Controllers\AdminPanel\AdminAuth;

use App\Eloquent\Admin;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin',['except' => ['logout']]);
    }

    //function to show admin login form
    public function showLoginForm()
    {
        return view('admin_panel.auth.login');
    }

    //function to login admin
    public function login(Request $request)
    {

        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')
            ->attempt([
                'email' => $request->email,
                'password' => $request->password
            ], $request->remember)) {

            $user = Admin::where('email', $request->email)->first();

            if ($user->active == false) {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->withErrors(['error' => 'Пользовотель не одобрен.']);
            }
            return redirect()->intended(route('admin.dashboard.index'));
        }
        return redirect()->back()
            ->withInput($request->only('email','remember'))
            ->withErrors([
                'email' => 'Некорректный email или пароль.'
            ]);
    }

    /* function to logout admin */
    public function logout(Request $request)
    {
        /*Auth::guard('admin')->logout();
        return redirect('/');*/

        Auth::guard('admin')->logout();

        //$request->session()->invalidate();

        return redirect()->route('admin.login');

    }


}
