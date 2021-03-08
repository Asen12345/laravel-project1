<?php

namespace App\Observers;

use App\Eloquent\MailTemplate;
use Storage;

class MailTemplateObserver
{
    /**
     * Handle the mail template "created" event.
     *
     * @param MailTemplate $mailTemplate
     * @return void
     */
    public function created(MailTemplate $mailTemplate)
    {
        //
    }

    /**
     * Handle the mail template "updated" event.
     *
     * @param MailTemplate $mailTemplate
     * @return void
     */
    public function updated(MailTemplate $mailTemplate)
    {
        /*Write template mail in file blade*/
        $file = fopen(resource_path().'/views/email/content/'. $mailTemplate->template_id . '.blade.php',"w");
        fwrite($file,$mailTemplate->getAttribute('text'));
        fclose($file);
    }

    /**
     * Handle the mail template "deleted" event.
     *
     * @param MailTemplate $mailTemplate
     * @return void
     */
    public function deleted(MailTemplate $mailTemplate)
    {
        //
    }

    /**
     * Handle the mail template "restored" event.
     *
     * @param MailTemplate $mailTemplate
     * @return void
     */
    public function restored(MailTemplate $mailTemplate)
    {
        //
    }

    /**
     * Handle the mail template "force deleted" event.
     *
     * @param MailTemplate $mailTemplate
     * @return void
     */
    public function forceDeleted(MailTemplate $mailTemplate)
    {
        //
    }
}
