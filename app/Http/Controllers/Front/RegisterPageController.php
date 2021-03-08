<?php

namespace App\Http\Controllers\Front;

use App\Eloquent\Company;
use App\Eloquent\CompanyType;
use App\Eloquent\GeoCity;
use App\Eloquent\GeoCountry;
use App\Eloquent\User;
use App\Eloquent\UserSocialProfile;
use App\Http\PageContent\Frontend\Register\RegisterPageContent;
use App\Repositories\Frontend\UserRepository;
use App\Rules\EmailRule;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class RegisterPageController extends Controller
{
    public function __construct() {

    }

    public function index() {
        $countries = GeoCountry::where('hidden', false)
            ->select('id', 'title', 'title_en')
            ->orderBy('position', 'asc')->get();
        $company_type = CompanyType::whereNotNull('id')
            ->orderBy('name', 'asc')->get();
        return view('front.page.register.register', [
            'countries'    => $countries,
            'company_type' => $company_type,
            'content_page' => (new RegisterPageContent())->registerPageContent()
        ]);

    }

    public function autoCompleteCity(Request $request) {
        $input = $request->input('city');
        $from_route = $this->getPreviousRoute();

        if ($from_route == 'front.page.people.experts.index' || $from_route == 'front.page.people.companies.index') {
            $data = GeoCity::where(function ($query) use ($input) {
                $query->where('title', 'LIKE', '%' . $input . '%')
                    ->orWhere('title_en', 'LIKE', '%' . $input . '%');
            })->select('title', 'id')->take(5)->get();
            $data = array_merge((array(['title' => 'Нет', 'id' => ''])), $data->toArray());
            //$clean_val = array(['title' => '', 'id' => '']);

        } else {
            $data = GeoCity::where(function ($query) use ($input) {
                $query->where('title', 'LIKE', '%' . $input . '%')
                    ->orWhere('title_en', 'LIKE', '%' . $input . '%');
            })
                ->where('country_id', $request->input('country'))
                ->take(5)->get();
        }


        return response()->json($data);
    }

    public function autoCompleteCompany(Request $request) {

        $input = $request->input('name');
        if ($request->input('lang') == 'company_rus') {

            $data = Company::where('name', 'LIKE', '%' . $input . '%')
                ->take(5)->get();

        } elseif ($request->input('lang') == 'company_strong') {

            $company = Company::where('name', trim($request->name))->first();
            $data = UserSocialProfile::where('company_id', $company->id)
                ->whereHas('user', function ($query) {
                    $query->where('permission', 'company');
                })->get()->isEmpty();

        } else {

            if ($request->exists('type')) {

                $data = $this->getUsersByPermission(
                    $request->input('name'),
                    $request->input('type')
                );

            } else {
                $data = Company::where(function ($query) use ($input) {
                    $query->where('name', 'LIKE', '%' . $input . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $input . '%');
                })->take(5)->get();
            }

        }

        return response()->json($data);
    }

    public function checkCompanyIsUser(Request $request) {
        $userProfile = UserSocialProfile::where('company_id', $request->id)
            ->whereHas('user', function ($query) {
                $query->where('permission', 'company');
            })->get();
        return response()->json(!$userProfile->isEmpty());
    }

    public function registerExpert(Request $request) {
        $request->session()->flash('type', 'expert');
        $this->validateExpertRegisterForm($request->all());
        (new UserRepository())->createUserExpert($request->all());
        return redirect()->route('front.home')->with('success', 'Пользователь зарегистрирован, ожидайте email на указанный адрес.');

    }

    public function registerCompany (Request $request) {
        $request->session()->flash('type', 'company');
        $this->validateCompanyRegisterForm($request->all());
        (new UserRepository())->createUserCompany($request->all());
        return redirect()->route('front.home')->with('success', 'Пользователь зарегистрирован, ожидайте email на указанный адрес.');
    }

    public function validateExpertRegisterForm ($request) {

        $rules = ([
            'firstname'  => ['required', 'string', 'max:255'],
            'lastname'   => ['required', 'string', 'max:255'],
            'country'    => ['required', 'integer'],
            'city_id'    => ['required', 'integer'],
            'agree'      => ['required', 'boolean', 'max:2'],
            'agree_data' => ['required', 'boolean', 'max:2'],
            'email'      => ['required', 'max:255', 'unique:users', new EmailRule],
            'password'   => ['required', 'string', 'confirmed'],
        ]);
        $messages = [
            'password.required' => 'Пароль обязателен к заполнению.',
            'firstname'         => 'Поле Имя обязательно для заполнения.',
            'lastname'          => 'Поле Фамилия обязательно для заполнения.',
            'confirmed'         => 'Пароли не совпадают.',
            'email.unique'      => 'Пользователь с таким Email уже существует.',
        ];

        $validator = Validator::make($request, $rules, $messages)->validate();

        return $validator;

    }

    public function validateCompanyRegisterForm ($request) {

        $rules = ([
            'country'    => ['required', 'integer', 'max:255'],
            'city_id'    => ['required', 'integer'],
            'agree'      => ['required', 'boolean', 'max:2'],
            'email'      => ['required', 'max:255', 'unique:users', new EmailRule],
            'password'   => ['required', 'string', 'confirmed'],
        ]);
        $messages = [
            'password.required' => 'Пароль обязателен к заполнению.',
            'confirmed'         => 'Пароли не совпадают.',
            'email.unique'      => 'Пользователь с таким Email уже существует.',
        ];

        $validator = Validator::make($request, $rules, $messages)->validate();

        return $validator;

    }

    /**
     * Get users list by its permission
     * Take (limit) 5 by default
     *
     * @param $name
     * @param $permission
     * @param int $take
     * @return mixed
     *
     * TODO: move to users repository
     */
    protected function getUsersByPermission($name, $permission, $take = 5)
    {
        return User::where('name', 'like', '%'.$name.'%')
            ->where('permission', $permission)
            ->take($take)->get();
    }
}
