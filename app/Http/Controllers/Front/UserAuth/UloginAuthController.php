<?php

namespace App\Http\Controllers\Front\UserAuth;

use App\Eloquent\User;
use App\Eloquent\UserSocialProfile;
use Auth;
use function GuzzleHttp\Psr7\str;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class UloginAuthController extends Controller
{
    public function login(Request $request)
    {
        //return response()->json($request->data);
        // Get information about user.
        //$data = file_get_contents('http://ulogin.ru/token.php?token=' . $request->token . '&host=' . url(''));

        //$user = json_decode($request->data, TRUE);

        $userData = User::where('email', $request->data['email'])->first();

        if (isset($userData->id)) {
            Auth::loginUsingId($userData->id, TRUE);
            //return redirect()->intended();
        } else {
            $pass = Str::random(8);
            $newUser = User::create([
                'name'          => $request->data['first_name'] . ' ' . $request->data['last_name'],
                'country'       => $request->data['country'],
                'email'         => $request->data['email'],
                'password'      => $pass,
                'open_password' => $pass,
                'active'        => true,
                'permission'    => 'social',
            ]);

            UserSocialProfile::create([
                'user_id'    => $newUser->id,
                'first_name' => $request->data['first_name'],
                'last_name'  => $request->data['last_name'],
                'country_id' => 1,
                'city_id'    => 1,
                'web_site'   => $request->data['profile'],
                'image'      => $request->data['photo_big']
            ]);
            Auth::loginUsingId($newUser->id, TRUE);
            //return redirect()->intended();

        }

    }
}
