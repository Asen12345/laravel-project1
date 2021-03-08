<?php

namespace App\Listeners;

use App\Eloquent\AlertAccount;
use App\Eloquent\User;
use App\Events\NoticeFriendAccountEvent;
use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoticeFriendAccountListeners
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NoticeFriendAccountEvent  $event
     * @return void
     */
    public function handle(NoticeFriendAccountEvent $event)
    {
        $settingTemplate = (new MailTemplateRepository())->getByColumn('new_friend', 'template_id');
        $subject = $settingTemplate->subject;
        $userFormSend = User::where('id', $event->friend->user_id)->first();
        $userToSend = User::where('id', $event->friend->friend_id)->first();

        if ($userToSend->invitations == true) {
            $data = [
                'subject' => $subject,
                'user_from' => $userFormSend->name,
                'user_to' => $userToSend->name,
                'link' => '<a href="' . route('front.setting.account', ['type' => 'friends']) . '">Читать</a>',
            ];

            AlertAccount::create([
                'type' => 'new_friend',
                'user_id' => $userToSend->id,
                'text' => view('email.content.new_friend', $data),
            ]);
        }
    }
}
