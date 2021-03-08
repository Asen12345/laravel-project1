<?php

namespace App\Providers;

use App\Eloquent\Admin;
use App\Eloquent\Permission;
use App\Eloquent\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is-admin', function ($user) {
            return $user->getTable() == 'admins' && $user->role == 'admin';
        });
        Gate::define('is-redactor', function ($user) {
            return $user->getTable() == 'admins' && $user->role == 'redactor';
        });

        Gate::define('is-social', function (User $user) {
            return $user->getTable() == 'users' && $user->permission == 'social';
        });

        Gate::define('is-subscriber-topic', function (User $user, $topic) {
            return $user->getTable() == 'users' && $topic->subscriber->contains('id', $user->id) ;
        });

        /*Redactor permission*/
        Gate::define('permission', function (Admin $admin, $permission) {
            if (auth()->user()->role == 'admin') {
                return true;
            } else {
                $permission =  Permission::where('name_id', $permission)->first();
                if ($admin->permissions->contains('permission_id', $permission->id)) {
                    return true;
                } else {
                    return false;
                }
            }
        });
        Gate::define('any-category', function (Admin $admin) {
            /*If has any category*/
            if (auth()->user()->role == 'admin') {
                return true;
            } else {
                return $admin->categoryPermission->isNotEmpty();
            }
        });




        /*User policy for see social link*/
        Gate::define('work-phone-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->work_phone_show === 'all') return true;
            if ($socialProfile->work_phone_show === 'none') return false;
            if ($socialProfile->work_phone_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('mobile-phone-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->mobile_phone_show === 'all') return true;
            if ($socialProfile->mobile_phone_show === 'none') return false;
            if ($socialProfile->mobile_phone_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('skype-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->skype_show === 'all') return true;
            if ($socialProfile->skype_show === 'none') return false;
            if ($socialProfile->skype_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('web-site-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->web_site_show === 'all') return true;
            if ($socialProfile->web_site_show === 'none') return false;
            if ($socialProfile->web_site_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('work-email-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->work_email_show === 'all') return true;
            if ($socialProfile->work_email_show === 'none') return false;
            if ($socialProfile->work_email_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('personal-email-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->personal_email_show === 'all') return true;
            if ($socialProfile->personal_email_show === 'none') return false;
            if ($socialProfile->personal_email_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('about-me-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->about_me_show === 'all') return true;
            if ($socialProfile->about_me_show === 'none') return false;
            if ($socialProfile->about_me_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });
        Gate::define('address-show', function (User $user, $userSocial) {
            $socialProfile = $userSocial->privacy;
            if ($user->id === $userSocial->id) return true;
            if ($socialProfile->address_show === 'all') return true;
            if ($socialProfile->address_show === 'none') return false;
            if ($socialProfile->address_show === 'friends') {
                return $user->friends->where('accepted', true)->contains('friend_id', $userSocial->id);
            };
            return true;
        });



    }
}
