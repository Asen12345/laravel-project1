<?php

namespace App\Repositories\Frontend;

use App\Eloquent\Company;
use App\Eloquent\User;
use App\Eloquent\UserPrivacy;
use App\Eloquent\UserSocialProfile;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function createUserExpert (array $data) {
        $private = !empty($data['private']) ? 1 : 0;

        if ($private === 1) {
            $company = null;
        } else {
            $this->validateExpert($data);
            $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
        }

        DB::beginTransaction();
        try {
            $user = $this->model->create([
                'name' => $data['firstname'] . ' ' . $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'open_password' => $data['password'],
                'invitations' => 1,
                'private'     => $private,
                'permission' => 'expert',
                'active' => 0,
                'block' => 0,
                'notifications_subscribed' => 1,
            ]);
            UserSocialProfile::create([
                'user_id'         => $user['id'],
                'company_id'      => $company['id'],
                'first_name'      => $data['firstname'],
                'last_name'       => $data['lastname'],
                'country_id'      => $data['country'],
                'city_id'         => $data['city_id'],
                'company_post'    => $data['company_post'] ?? ''
            ]);
            UserPrivacy::create([
                'user_id'         => $user['id'],
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }


        return $user;

    }

    public function createUserCompany (array $data) {

        $this->validateCompany($data);
        $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);

        DB::beginTransaction();
        try {
            $user = $this->model->create([
                'name'          => $company->name,
                'email'         => $data['email'],
                'password'      => Hash::make($data['password']),
                'open_password' => $data['password'],
                'invitations'   => 1,
                'private'       => 0,
                'permission'    => 'company',
                'active'        => 0,
                'block'         => 0,
                'notifications_subscribed' => 1,
            ]);
            UserSocialProfile::create([
                'user_id'         => $user['id'],
                'first_name'      => null,
                'last_name'       => null,
                'country_id'      => $data['country'],
                'city_id'         => $data['city_id'],
                'company_id'      => $company->id,
            ]);
            UserPrivacy::create([
                'user_id'         => $user->id,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return $user;
    }

    public function companySaveIfNot ($company_name_rus, $company_name_en, $company_type) {

        $company = Company::firstOrCreate([
            'name'    => $company_name_rus,
            'name_en' => $company_name_en,
        ], [
            'name'    => $company_name_rus,
            'name_en' => $company_name_en,
            'type_id' => $company_type,
        ]);

        return $company;
    }

    private function validateExpert ($array){
        $rules = ([
            'company_rus'   => ['required', 'string'],
            'company_type'  => ['required', 'integer'],
            'company_post'  => ['required', 'string'],
        ]);
        $messages = [
            'company_rus.required' => 'Не заполнено поле "Компания (русское написание)"',
            'company_type.required' => 'Не заполнено поле "Сфера деятельности компании"',
            'company_post.required' => 'Не заполнено поле "Должность"',
        ];

        Validator::make($array, $rules, $messages)->validate();
    }
    private function validateCompany ($array){
        $rules = ([
            'company_rus'   => ['required', 'string'],
            'company_type'  => ['required', 'integer'],
        ]);
        $messages = [
            'company_rus.required' => 'Не заполнено поле "Компания (русское написание)"',
            'company_type.required' => 'Не заполнено поле "Сфера деятельности компании"',
        ];

        Validator::make($array, $rules, $messages)->validate();
    }

}
