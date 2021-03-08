<?php

namespace App\Repositories\Frontend\People;

use App\Eloquent\Company;
use App\Eloquent\User;
use App\Eloquent\UserSocialProfile;
use App\Jobs\NewUserPasswordJob;
use App\Rules\EmailRule;
use App\Rules\WebDomain;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Validator;

/**
 * Class CompanyRepository.
 */
class PeopleRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function filterCompany($value) {
        return $this->model->where('active', true)
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
                        } if ($key === 'company_id') {
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

    public function updateUserCompany (array $data) {
        $rules = ([
            'country'                 => ['required', 'string', 'max:255'],
            'city_id'                 => ['required', 'integer'],
            'contacts.web_site'       => ['nullable', new WebDomain()],
            'company_type'            => ['required_with:company_rus', 'integer'],
            'contacts.work_email'     => ['nullable', 'max:255', new EmailRule],
        ]);
        $messages = [
            'company_type.required'      => 'Выберете вид деятельность совпадают.',
        ];
        $names = [
            'company_type'               => "'Сфера деятельности компании'",
            'company_rus'                => "'Название компании'",
            'contacts.work_email'        => "'Корпаративный Email'",
        ];
        Validator::make($data, $rules, $messages, $names)->validate();


        if (!empty($data['email'])) {
            $data->except('email');
        }

        DB::beginTransaction();
        try {

            $company = $this->model->where('id', $data['user_id'])->first()->company;
            $company->update([
                'name'    => $data['company_rus'],
                'name_en' => $data['company_en'],
                'type_id' => $data['company_type'],
            ]);

            if (!empty($data['password'])) {
                $user = $this->model->where('id', $data['user_id'])
                    ->update([
                            'name' => $company->name ?? null,
                            'password' => Hash::make($data['password']),
                            'open_password' => $data['password'],
                            'permission' => 'company',
                        ]
                    );
                $change_pass = true;
                NewUserPasswordJob::dispatch($this->model->find($data['user_id']), 'password_change_admin');
            } else {
                $user = $this->model->where('id', $data['user_id'])
                    ->update([
                            'name'                     => $company->name ?? null,
                            'permission'               => 'company',
                        ]
                    );
                $change_pass = false;
            }

            UserSocialProfile::where('user_id', $data['user_id'])
                ->update([
                        'user_id'         => $data['user_id'],
                        'company_id'      => $company->id ?? null,
                        'country_id'      => $data['country'],
                        'city_id'         => $data['city_id'],
                        'image'           => $data['image'],
                        'work_phone'      => $data['contacts']['work_phone'] ?? null,
                        'mobile_phone'    => $data['contacts']['mobile_phone'] ?? null,
                        'skype'           => $data['contacts']['skype'],
                        'web_site'        => $data['contacts']['web_site'],
                        'address'         => $data['contacts']['address'],
                        'work_email'      => $data['contacts']['work_email'] ?? null,
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

    public function companySaveIfNot ($company_name_rus, $company_name_en, $company_type)
    {
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

}
