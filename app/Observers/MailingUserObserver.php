<?php

namespace App\Observers;

use App\Eloquent\MailingUser;
use App\Jobs\MailingAdminJob;

class MailingUserObserver
{
    /**
     * Handle the mailing user "created" event.
     *
     * @param MailingUser $mailingUser
     * @return void
     */
    public function created(MailingUser $mailingUser)
    {
        MailingAdminJob::dispatch($mailingUser);
    }

    /**
     * Handle the mailing user "updated" event.
     *
     * @param MailingUser $mailingUser
     * @return void
     */
    public function updated(MailingUser $mailingUser)
    {
        //
    }

    /**
     * Handle the mailing user "deleted" event.
     *
     * @param MailingUser $mailingUser
     * @return void
     */
    public function deleted(MailingUser $mailingUser)
    {
        //
    }

    /**
     * Handle the mailing user "restored" event.
     *
     * @param MailingUser $mailingUser
     * @return void
     */
    public function restored(MailingUser $mailingUser)
    {
        //
    }

    /**
     * Handle the mailing user "force deleted" event.
     *
     * @param MailingUser $mailingUser
     * @return void
     */
    public function forceDeleted(MailingUser $mailingUser)
    {
        //
    }
}
