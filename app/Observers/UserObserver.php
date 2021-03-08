<?php

namespace App\Observers;

use App\Eloquent\NotificationSubscriber;
use App\Eloquent\UnsubscribedUser;
use App\Eloquent\User;
use App\Jobs\RegisterNewUserJob;

class UserObserver
{

    /**
     * @param User $user
     * @return void
     */
    public function creating(User $user) {

        //$user->open_password = $user->getAttribute('password');
        //$user->password = bcrypt($user->getAttribute('password'));

    }
    /**
     * Handle the user "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        /*Writing to all job to send a message to Admin*/
        RegisterNewUserJob::dispatch($user, 'send_admin');
        if ($user->getAttribute('notifications_subscribed') == true) {
            NotificationSubscriber::create([
                'email' => $user->getAttribute('email')
            ]);
        }
    }

    public function updating (User $user) {

    }
    /**
     * Handle the user "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {

        /*If change 'active' by 'true' -> send mail "your account is approved"*/
        if ($user->isDirty('active') && $user->getAttribute('active') == true && $user->getAttribute('permission') == 'expert') {
            RegisterNewUserJob::dispatch($user, 'send_user');
        }

        if ($user->isDirty('notifications_subscribed') && $user->getAttribute('notifications_subscribed') == false) {
            NotificationSubscriber::where('email', $user->email)->delete();
            UnsubscribedUser::create(['email' => $user->email]);
        }
        if ($user->isDirty('notifications_subscribed') && $user->getAttribute('notifications_subscribed') == true) {
            NotificationSubscriber::create(['email' => $user->email]);
            UnsubscribedUser::where('email', $user->email)->delete();
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }

}
