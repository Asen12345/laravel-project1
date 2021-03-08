<?php

namespace App\Repositories\Back;

use App\Eloquent\Company;
use App\Eloquent\User;
use App\Eloquent\UserPrivacy;
use App\Eloquent\UserSocialProfile;
use App\Jobs\NewUserPasswordJob;
use App\Rules\EmailRule;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

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

    public function multiSort ($value) {
        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'page') {
                    continue;
                }
                if (is_null($val)) {
                    $query->where($key, 'LIKE', '%' .''. '%');
                } elseif (Str::contains($key, '_id')) {
                    $query->where($key, $val);
                } else {
                    if ($key === 'name') {
                        $query->where(function ($query) use ($val) {
                            $query->where('name', 'LIKE', '%' . $val . '%')
                                ->orWhere('email', 'LIKE', '%' . $val . '%')
                                ->orWhereHas('company', function ($query) use ($val) {
                                    $query->where('name', 'LIKE', '%' . $val . '%');
                                });
                        });
                    } else {
                        $query->where($key, 'LIKE', '%' . $val . '%');
                    }
                }
            }
        });

    }

    public function createUserExpert (array $data) {
        $active = !empty($data['active']) ? 1 : 0;
        $block = !empty($data['block']) ? 1 : 0;
        $notifications_subscribed = !empty($data['notifications_subscribed']) ? 1 : 0;
        $invitations = !empty($data['invitations']) ? 1 : 0;
        $private = !empty($data['private']) ? 1 : 0;

        if ($private === 1) {
            $company = null;
        } else {
            $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
        }

        $this->validateRegisterForm($data);

        DB::beginTransaction();
        try {
            $user = $this->model->create([
                'name' => $data['firstname'] . ' ' . $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'open_password' => $data['password'],
                'invitations' => $invitations,
                'private'     => $private,
                'permission' => 'expert',
                'active' => $active,
                'block' => $block,
                'notifications_subscribed' => $notifications_subscribed,
            ]);

            UserSocialProfile::create([
                'user_id'         => $user->id,
                'company_id'      => $company->id ?? null,
                'first_name'      => $data['firstname'],
                'last_name'       => $data['lastname'],
                'country_id'      => $data['country_id'],
                'city_id'         => $data['city_id'],
                'company_post'    => $data['company_post'] ?? '',
                'image'           => $data['image'],
            ]);
            UserPrivacy::create([
               'user_id' => $user->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            dd($id_index);
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return $user;
    }

    public function updateUserExpert (array $data) {
        $active = !empty($data['active']) ? 1 : 0;
        $block = !empty($data['block']) ? 1 : 0;
        //$notifications_subscribed = !empty($data['notifications_subscribed']) ? 1 : 0;
        //$invitations = !empty($data['invitations']) ? 1 : 0;
        $private = !empty($data['private']) ? 1 : 0;

        if ($private === 1) {
            $company = null;
        } elseif (empty($data['company_rus']) && empty($data['company_en'])) {
            $company = null;
        } else {
            $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
        }

        $this->validateRegisterForm($data);
        DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $user = $this->model->find($data['user_id'])
                    ->update([
                        'name' => $data['firstname'] . ' ' . $data['lastname'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                        'open_password' => $data['password'],
                        //'invitations' => $invitations,
                        'private'     => $private,
                        'permission' => 'expert',
                        'active' => $active,
                        'block' => $block,
                        //'notifications_subscribed' => $notifications_subscribed,
                    ]
                );
                /*If in data have password fire job for send new password to user*/
                NewUserPasswordJob::dispatch($this->model->find($data['user_id']), 'password_change_admin');

            } else {
                $user = $this->model->find($data['user_id'])
                    ->update([
                        'name'                     => $data['firstname'] . ' ' . $data['lastname'],
                        'email'                    => $data['email'],
                        //'invitations'              => $invitations,
                        'private'                  => $private,
                        'permission'               => 'expert',
                        'active'                   => $active,
                        'block'                    => $block,
                        //'notifications_subscribed' => $notifications_subscribed,
                    ]
                );
            }
            UserSocialProfile::where('user_id', $data['user_id'])
                ->update([
                    'user_id'         => $data['user_id'],
                    'company_id'      => $company->id ?? null,
                    'first_name'      => $data['firstname'],
                    'last_name'       => $data['lastname'],
                    'country_id'      => $data['country_id'],
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
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return $user;
    }

    public function createUserCompany (array $data) {
        $active = !empty($data['active']) ? 1 : 0;
        $block = !empty($data['block']) ? 1 : 0;
        $notifications_subscribed = !empty($data['notifications_subscribed']) ? 1 : 0;
        $invitations = !empty($data['invitations']) ? 1 : 0;
        $private = 0;
        $this->validateRegisterForm($data);
        $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
        DB::beginTransaction();
        try {
            $user = $this->model->create([
                'name' => $data['company_rus'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'open_password' => $data['password'],
                'invitations' => $invitations,
                'private'     => $private,
                'permission' => 'company',
                'active' => $active,
                'block' => $block,
                'notifications_subscribed' => $notifications_subscribed,
            ]);
            UserSocialProfile::create([
                'user_id'         => $user->id,
                'company_id'      => $company->id,
                'first_name'      => $data['firstname'],
                'last_name'       => $data['lastname'],
                'country_id'      => $data['country_id'],
                'city_id'         => $data['city_id'],
                'image'           => $data['image'],
            ]);
            UserPrivacy::create([
                'user_id' => $user->id
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

    public function updateUserCompany (array $data) {
        $active = !empty($data['active']) ? 1 : 0;
        $block = !empty($data['block']) ? 1 : 0;
        $send_notification = !empty($data['send_notification']) ? 1 : 0;
        //$invitations = !empty($data['invitations']) ? 1 : 0;
        $private = !empty($data['private']) ? 1 : 0;

        //$company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
        $this->validateExpertRegisterForm($data);
        DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $user = $this->model->where('id', $data['user_id'])
                    ->update([
                            'name' => $data['company_rus'],
                            'email' => $data['email'],
                            'password' => Hash::make($data['password']),
                            'open_password' => $data['password'],
                            //'invitations' => $invitations,
                            'private'     => $private,
                            'permission' => 'company',
                            'active' => $active,
                            'block' => $block,
                            'send_notification' => $send_notification,
                        ]
                    );
                /*If in data have password fire job for send new password to user*/
                NewUserPasswordJob::dispatch($this->model->find($data['user_id']), 'password_change_admin');
            } else {
                $user = $this->model->where('id', $data['user_id'])
                    ->update([
                            'name'                     => $data['company_rus'],
                            'email'                    => $data['email'],
                            //'invitations'              => $invitations,
                            'private'                  => $private,
                            'permission'               => 'company',
                            'active'                   => $active,
                            'block'                    => $block,
                            'send_notification'        => $send_notification,
                        ]
                    );
            }
            $company = $this->model->where('id', $data['user_id'])->first()->company;
            if (empty($company)) {
                $company = $this->companySaveIfNot($data['company_rus'], $data['company_en'], $data['company_type']);
            }
            $company->update([
                'name'    => $data['company_rus'],
                'name_en' => $data['company_en'],
                'type_id' => $data['company_type'],
            ]);

            UserSocialProfile::where('user_id', $data['user_id'])
                ->update([
                        'user_id'         => $data['user_id'],
                        'company_id'      => $company->id,
                        'first_name'      => $data['firstname'],
                        'last_name'       => $data['lastname'],
                        'country_id'      => $data['country_id'],
                        'city_id'         => $data['city_id'],
                        'image'           => $data['image'],
                        'work_phone'      => $data['contacts']['work_phone'],
                        'mobile_phone'    => $data['contacts']['mobile_phone'],
                        'skype'           => $data['contacts']['skype'],
                        'web_site'        => $data['contacts']['web_site'],
                        'work_email'      => $data['contacts']['work_email'],
                        'personal_email'  => $data['contacts']['personal_email'],
                        'address'         => $data['contacts']['address'] ?? null,
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
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
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

    public function validateCompanyRegisterForm ($request) {

        $rules = ([
            'email'                   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request['user_id']), new EmailRule],
            'contacts.work_email'     => ['nullable', 'max:255', new EmailRule],
            'contacts.personal_email' => ['nullable', 'max:255', new EmailRule],
        ]);
        $messages = [

        ];

        $names = [
            'contacts.work_email'        => "'Рабочий Email'",
            'contacts.personal_email'    => "'Личный Email'",
        ];

        $validator = Validator::make($request, $rules, $messages, $names)->validate();

        return $validator;

    }

    public function validateExpertRegisterForm ($request) {
        $rules = ([
            'email'                   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request['user_id']), new EmailRule],
            'contacts.work_email'     => ['nullable', 'max:255', new EmailRule],
            'contacts.personal_email' => ['nullable', 'max:255', new EmailRule],
        ]);
        $messages = [
        ];

        $names = [
            'contacts.work_email'        => "'Рабочий Email'",
            'contacts.personal_email'    => "'Личный Email'",
        ];

        $validator = Validator::make($request, $rules, $messages, $names)->validate();

        return $validator;

    }

    public function validateRegisterForm ($request) {
        $rules = ([
            'email'                   => ['required', 'email', 'max:255', new EmailRule],
            'contacts.work_email'     => ['nullable', 'max:255', new EmailRule],
            'contacts.personal_email' => ['nullable', 'max:255', new EmailRule],
        ]);
        $messages = [
        ];

        $names = [
            'contacts.work_email'        => "'Рабочий Email'",
            'contacts.personal_email'    => "'Личный Email'",
        ];

        $validator = Validator::make($request, $rules, $messages, $names)->validate();

        return $validator;

    }

}
