<?php

namespace App\Http\Controllers\Front\Friend;

use App\Eloquent\Friends;
use App\Events\NoticeFriendAccountEvent;
use App\Events\NoticeMessageAccountEvent;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{

    private $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (!\Auth::check()) {
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваша сессия была закрыта. Войдите в аккаунт.'
                ]);
            } else {
                $this->user = auth()->user();
                if ($this->user->active !== true) {
                    return redirect()->back()->withErrors([
                        'error' => 'Учетная запись не одобрена, ожидайте Email письма об активации!'
                    ]);
                }
                return $next($request);
            }
        });
    }

    public function addFriend(Request $request) {
        $friend = Friends::firstOrNew(['user_id' => $this->user->id, 'friend_id' => $request->id]);
        if ($request->id == $this->user->id) {
            return response(['result' => 'error', 'text' => 'Нельзя отправить запрос самому себе.']);
        }
        if (!empty($friend->id)) {
            return response(['result' => 'has', 'text' => 'Запрос уже был отправлен.']);
        } else {
            $friend->save();

            event(new NoticeFriendAccountEvent($friend));

            return response(['result' => 'save', 'text' => 'Запрос на добавление в друзья отправлен.']);
        }
    }

    public function acceptFriend(Request $request) {
        $friend_record = Friends::where('user_id', $request->user_id)->where('friend_id', $this->user->id)->first();
        $friend_record_rev = Friends::where('user_id', $this->user->id)
            ->where('friend_id', $request->user_id)
            ->where('accepted', true)
            ->first();

        if (empty($friend_record->id)) {
            return redirect()->back()
                ->withErrors(['error' => 'Запрос не найден.']);
        }
        if (!empty($friend_record_rev)){
            return redirect()->back()
                ->withErrors(['error' => 'Запрос уже одобрен']);
        }

        DB::beginTransaction();
        try {
            /*Create a reverse record of the confirmation*/
            Friends::updateOrCreate([
                'user_id'   => $friend_record->friend_id,
                'friend_id' => $friend_record->user_id,
            ], [
                'accepted'  => true
            ]);
            $friend_record->update([
                'accepted'  => true
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()->back()->with('success', 'Пользователь добавлен в друзья.');

    }

    public function cancelFriend(Request $request) {
        $friend_record = Friends::where('user_id', $request->user_id)->where('friend_id', $this->user->id)->first();
        $friend_record_rev = Friends::where('user_id', $this->user->id)->where('friend_id', $request->user_id)->first();

        if (empty($friend_record->id)) {
            return redirect()->back()
                ->withErrors(['error' => 'Запрос не найден.']);
        }

        DB::beginTransaction();
        try {
            $friend_record->delete();
            if (!empty($friend_record_rev)) {
                $friend_record_rev->delete();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()->back()->with('success', 'Пользователь удален из друзей.');

    }

    public function deleteFriend(Request $request) {
        $friend_record = Friends::where('user_id', $request->user_id)->where('friend_id', $this->user->id)->first();
        $friend_record_rev = Friends::where('user_id', $this->user->id)->where('friend_id', $request->user_id)->first();
        if (empty($friend_record->id) || empty($friend_record_rev->id)) {
            return redirect()->back()
                ->withErrors(['error' => 'Запрос не найден.']);
        }
        DB::beginTransaction();
        try {
            $friend_record->delete();
            $friend_record_rev->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()->back()->with('success', 'Пользователь удален из друзей.');
    }

}
