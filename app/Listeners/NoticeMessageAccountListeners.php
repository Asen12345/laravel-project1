<?php

namespace App\Listeners;

use App\Eloquent\AlertAccount;
use App\Eloquent\User;
use App\Events\NoticeMessageAccountEvent;

class NoticeMessageAccountListeners
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
     * @param NoticeMessageAccountEvent $event
     * @return void
     */
    public function handle(NoticeMessageAccountEvent $event)
    {
        $subject = $event->message->thread->subject;
        $userFormSend = $event->message->userSend;
        $userToSend = User::where('id', $event->message->user_to_id)->first();

        if ($userToSend->invitations == true) {
            $data = [
                'message_body' => $event->message->body,
                'subject'      => $subject,
                'user_from'    => $userFormSend->name,
                'user_to'      => $userToSend->name,
                'link'         => '<a href="' . route('front.setting.account.message.page', ['id' => $event->message->thread->id]) . '">Читать</a>',
            ];

            AlertAccount::create([
                'type' => 'new_message',
                'user_id' => $userToSend->id,
                'text' => view('email.content.new_message', $data),
            ]);
        }
    }
}
