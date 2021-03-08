<?php

namespace App\Http\ViewComposers;

use App\Eloquent\AlertAccount;
use App\Eloquent\Banner;
use App\Eloquent\Mailing;
use App\Eloquent\MailingUser;
use Carbon\Carbon;
use Illuminate\View\View;

class NoticeComposer
{
    public function compose(View $view)
    {

        if (auth()->check()){

            $user = auth()->user();

            $messagesNotRead = $user->messages()
                ->whereNull('read_at')
                ->where('user_to_id', $user->id)
                ->count();
            $adminNotRead = MailingUser::where('user_id', $user->id)->whereNull('read_at')->count();
            $alertNotRead = AlertAccount::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();
            $messages_not_reads = $messagesNotRead + $adminNotRead + $alertNotRead;

            $bell = AlertAccount::where('user_id', $user->id)
                ->where(function ($query) use ($user) {
                    $query->where('type', 'new_comments')
                        ->whereNull('read_at')
                        ->where('user_id', $user->id);
                })
                ->orWhere(function ($query) {
                    $query->where('type', 'new_friend')
                        ->whereNull('read_at');
                })
                ->orWhere(function ($query) use ($user) {
                    $query->where('type', 'new_topic_subscriber')
                        ->whereNull('read_at')
                        ->where('user_id', $user->id);
                })
                ->count();

            $messageBell = AlertAccount::where('user_id', $user->id)
                ->where(function ($query) use ($user) {
                    $query->where('type', 'new_message')
                        ->whereNull('read_at')
                        ->where('user_id', $user->id);
                })
                ->orWhere(function ($query) use ($user) {
                    $query->where('type', 'new_post')
                        ->whereNull('read_at')
                        ->where('user_id', $user->id);
                })
                ->count();

            return $view->with([
                'bell'                       => $bell,
                'messageBell'                => $messageBell,
                'messages_not_reads_count'   => $messages_not_reads,
            ]);

        }




    }
}