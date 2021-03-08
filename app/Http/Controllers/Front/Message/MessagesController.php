<?php

namespace App\Http\Controllers\Front\Message;

use App\Eloquent\AlertAccount;
use App\Eloquent\Mailing;
use App\Eloquent\MailingUser;
use App\Eloquent\Message;
use App\Eloquent\User;
use App\Http\PageContent\Frontend\Setting\AccountPageContent;
use App\Repositories\Frontend\Message\MessageRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    /**
     * @var Authenticatable|null
     */
    protected $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваша сессия была закрыта. Войдите в аккаунт.'
                ]);
            } else {
                $this->user = auth()->user();
                if ($this->user->active !== true) {
                    return redirect()->back()->withErrors([
                        'error' => 'Учетная запись не одобрена, ожидайте Email письма об активации.'
                    ]);
                }
                return $next($request);
            }
        });
    }

    public function sendFirst(Request $request) {
        $input = $request->all();
        $thread = (new MessageRepository())->firstSendMessage($input);

        if (!empty($thread['error'])) {
            return redirect()->back()->withErrors([
                'error' => $thread['error']
            ]);
        } else {
            return redirect()->back()->with('success', 'Сообщение отправлено.');
        }
    }

    public function messageSend(Request $request, $id) {
        $user = User::where('id', $this->user->id)->first();
        $thread = $user->thread->where('id', $id)->first();
        $userTo = $thread->messageParticipants()->where('user_id', '!=', $user->id)->first();
        $message = Message::create([
            'thread_id'  => $thread->id,
            'user_id'    => $this->user->id,
            'user_to_id' => $userTo->user_id,
            'body'       => $request->body
        ]);
        if (empty($message->id)){
            return redirect()->back()->withErrors([
                'error' => 'При отправке произошла ошибка.'
            ]);
        } else {
            return redirect()->back()->with('success', 'Сообщение отправлено.');
        }

    }

    public function messagesAdministration() {

        $user = User::where('id', $this->user->id)->with(['thread'])->first();

        $mailingAdmin = MailingUser::where('user_id', $this->user->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('read_at', 'ASC')
            ->paginate(30);


        foreach ($mailingAdmin as $item) {
            if ($item->read_at === null) {
                $read = false;
            } else {
                $read = true;
            }
            $item->update([
                'read_at'  => Carbon::now()
            ]);
            $item['read'] = $read;
        }
        return view('front.page.setting.page.admin-messages', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'message',
            'messages_count'           => $user->allMessages()->count(),
            'mailingAdmin'             => $mailingAdmin,
        ]);
    }


    public function alertMessagePage() {

        $user = User::where('id', $this->user->id)->with(['thread'])->first();

        $alertMessaged = AlertAccount::where('user_id', $this->user->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('read_at', 'ASC')
            ->paginate(30);

        foreach ($alertMessaged as $item) {
            if ($item->read_at === null) {
                $read = false;
            } else {
                $read = true;
            }
            $item->where('read_at', null)
                ->update([
                    'read_at' => Carbon::now()
                ]);
            $item['read'] = $read;
        }

        $mailingAdmin = Mailing::whereHas('mailingUsers', function ($query) {
            $query->where('user_id', $this->user->id)
            ->whereNull('read_at');
        })->count();

        return view('front.page.setting.page.page-alert-messages', [
            'user'                     => $user,
            'content_page'             => (new AccountPageContent())->mainPageContent(),
            'menu_setting_active'      => 'message',
            'messages_count'           => $user->allMessages()->count(),
            'mailingAdmin'             => $mailingAdmin,
            'alertMessaged'            => $alertMessaged,
        ]);
    }

    public function messagePage(Request $request, $id) {
        if ($request->method() == 'POST') {
            $user = User::where('id', $this->user->id)->first();
            $message_admin = Mailing::whereHas('mailingUsers', function ($query) {
                $query->where('user_id', $this->user->id);
            })->first();

            $message_admin->mailingUsers()->where('user_id', $this->user->id)->update([
                'read_at' => Carbon::now()
            ]);

            return view('front.page.setting.page.page-message-admin', [
                'user' => $user,
                'content_page'        => (new AccountPageContent())->mainPageContent($this->user),
                'menu_setting_active' => 'message',
                'message_admin'       => $message_admin,
            ]);

        } else {
            $user = User::where('id', $this->user->id)->first();
            $thread = $user->thread->where('id', $id)->first();
            $messages = $thread->messages()->orderBy('created_at', 'asc')->paginate(20);
            $thread->messages()
                ->whereNull('read_at')
                ->where('messages.user_to_id', '=', $this->user->id)
                ->update(
                    [
                        'read_at' => Carbon::now()
                    ]
                );

            return view('front.page.setting.page.page-message', [
                'user' => $user,
                'content_page' => (new AccountPageContent())->mainPageContent($this->user),
                'menu_setting_active' => 'message',
                'messages' => $messages,
                'thread' => $thread,
            ]);
        }
    }

    public function downloadFile(Request $request, $id) {
        $message = Mailing::whereHas('mailingUsers', function ($query) {
            $query->where('user_id', $this->user->id);
        })->where('id', $id)->first();
        $entry = $message->file_patch;
        $pathToFile = public_path() . '/storage/file_upload/' . $entry;
        return response()->download($pathToFile);
    }

    public function threadDestroy($id)
    {
        $user = User::where('id', $this->user->id)->first();
        $thread = $user->thread->where('id', $id)->first();
        $thread->messages()->delete();
        $thread->messageParticipants()->delete();
        $thread->delete();
        return redirect()->back()->with('success', 'Ветка удалена.');
    }

    public function messageDelete($thread, $id)
    {
        $user = User::where('id', $this->user->id)->first();
        $thread = $user->thread->where('id', $thread)->first();
        $thread->messages()->where('id', $id)->delete();

        if ($thread->messages->isEmpty()) {
            $thread->messages()->delete();
            $thread->messageParticipants()->delete();
            $thread->delete();
            return redirect()->route('front.setting.account', ['type' => 'message']);
        }

        return redirect()->back()->with('success', 'Сообщение удалено.');
    }

    public function alertMessageDestroy($id)
    {
        AlertAccount::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Уведомление удалено.');
    }

    public function threadAdminDestroy($id)
    {
        $thread = Mailing::where('id', $id)->first();
        $thread->mailingUsers()->where('user_id', $this->user->id)->delete();
        return redirect()->back()->with('success', 'Ветка удалена.');
    }

}
