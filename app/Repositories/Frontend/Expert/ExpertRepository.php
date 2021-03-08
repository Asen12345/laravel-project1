<?php

namespace App\Repositories\Frontend\Expert;

use App\Eloquent\Company;
use App\Eloquent\UserSocialProfile;
use App\Jobs\NewUserPasswordJob;
use App\Rules\EmailRule;
use App\Rules\WebDomain;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Eloquent\User;
use Validator;

/**
 * Class ExpertRepository.
 */
class ExpertRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function filterExpert($value) {
        return $this->model->where('permission', 'expert')
            ->where('active', true)
            ->withCount(['blogPosts as posts_count' => function($query){
                $query->where('published', true);
            }])
            ->where(function ($query) use ($value) {
                foreach ($value as $key => $val) {
                    if (is_null($val)) {
                        continue;
                    } else {
                        if ($key === 'name') {
                            $query->where(function ($query) use ($val, $value) {
                                $query->where('name', 'LIKE', '%' . $val . '%')
                                    ->orWhere('email', 'LIKE', '%' . $val . '%');
                            });
                        } if ($key === 'company_type_id') {
                            $query->whereHas('company', function ($query) use ($val) {
                                $query->where('type_id', '=', $val);
                            });
                        }if ($key === 'company_id') {
                            $query->whereHas('company', function ($query) use ($val) {
                                $query->where('companies.name', 'LIKE', '%' . $val . '%')
                                    ->orWhere('companies.name_en', 'LIKE', '%' . $val . '%');
                            });
                        } if ($key === 'country_id' || $key === 'city_id') {
                            $query->whereHas('socialProfile', function ($query) use ($key, $val) {
                                $query->where($key, '=', $val);
                            });
                        }
                    }
                }
            })
            ->with(['news'], function ($query){
                $query->where('published', true);
            });
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateUserExpert (array $data) {
        $rules = ([
            'first_name'              => ['required', 'string', 'max:255'],
            'last_name'               => ['required', 'string', 'max:255'],
            'country'                 => ['required', 'string', 'max:255'],
            'city_id'                 => ['required', 'integer'],
            'contacts.web_site'       => ['nullable', new WebDomain()],
            'company_type'            => ['nullable', 'required_with:company_rus', 'integer'],
            'contacts.work_email'     => ['nullable', 'max:255', new EmailRule],
            'contacts.personal_email' => ['nullable', 'max:255', new EmailRule],
        ]);
        $messages = [
            'first_name'                 => 'Поле Имя обязательно для заполнения.',
            'last_name'                  => 'Поле Фамилия обязательно для заполнения.',
            'company_type.required'      => 'Выберете вид деятельность совпадают.',
        ];
        $names = [
            'company_type'               => "'Сфера деятельности компании'",
            'company_rus'                => "'Название компании'",
            'contacts.work_email'        => "'Рабочий Email'",
            'contacts.personal_email'    => "'Личный Email'",
        ];
        Validator::make($data, $rules, $messages, $names)->validate();


        if (!empty($data['email'])) {
            $data->except('email');
        }
        $private = !empty($data['private']) ? 1 : 0;
        if ($private === 1) {
            $company = null;
        } elseif (empty($data['company_rus']) && empty($data['company_en'])) {
            $company = null;
        } else {
            $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
        }

        DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $user = $this->model->where('id', $data['user_id'])
                    ->update([
                            'name' => $data['first_name'] . ' ' . $data['last_name'],
                            'password' => Hash::make($data['password']),
                            'open_password' => $data['password'],
                            'private'     => $private,
                            'permission' => 'expert',
                        ]
                    );
                $change_pass = true;
                NewUserPasswordJob::dispatch($this->model->find($data['user_id']), 'password_change_admin');
            } else {
                $user = $this->model->where('id', $data['user_id'])
                    ->update([
                            'name'                     => $data['first_name'] . ' ' . $data['last_name'],
                            'private'                  => $private,
                            'permission'               => 'expert',
                        ]
                    );
                $change_pass = false;
            }
            UserSocialProfile::where('user_id', $data['user_id'])
                ->update([
                        'user_id'         => $data['user_id'],
                        'company_id'      => $company->id ?? null,
                        'first_name'      => $data['first_name'],
                        'last_name'       => $data['last_name'],
                        'country_id'      => $data['country'],
                        'city_id'         => $data['city_id'],
                        'company_post'    => $data['company_post'],
                        'image'           => $data['image'],
                        'work_phone'      => $data['contacts']['work_phone'],
                        'mobile_phone'    => $data['contacts']['mobile_phone'],
                        'skype'           => $data['contacts']['skype'],
                        'web_site'        => $data['contacts']['web_site'],
                        'work_email'      => $data['contacts']['work_email'],
                        'personal_email'  => $data['contacts']['personal_email'],
                        'about_me'        => $data['contacts']['about_me'],
                        'face_book'       => $data['contacts']['face_book'],
                        'linked_in'       => $data['contacts']['linked_in'],
                        'v_kontakte'      => $data['contacts']['v_kontakte'],
                        'odnoklasniki'    => $data['contacts']['odnoklasniki']
                    ]
                );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();

            return array('error' => $id_index);
        }
        $request = array('user' => $user, 'change_pass' => $change_pass);
        return $request;
    }

    public function companySaveIfNot ($company_name_rus, $company_name_en, $company_type) {

        $company = Company::where('name', $company_name_rus)->first();
        if (!empty($company)) {
            $userCompany = $company->users()->where('permission', 'company')->get();
            if ($userCompany->isEmpty()) {
                $company = Company::firstOrCreate([
                    'name'    => $company_name_rus,
                    'name_en' => $company_name_en,
                ], [
                    'name'    => $company_name_rus,
                    'name_en' => $company_name_en,
                    'type_id' => $company_type,
                ]);
            }
        } else {
            $company = Company::firstOrCreate([
                'name'    => $company_name_rus,
                'name_en' => $company_name_en,
            ], [
                'name'    => $company_name_rus,
                'name_en' => $company_name_en,
                'type_id' => $company_type,
            ]);
        }



        return $company;
    }
}
