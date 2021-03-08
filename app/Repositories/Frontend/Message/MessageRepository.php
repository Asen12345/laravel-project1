<?php

namespace App\Repositories\Frontend\Message;

use App\Events\NoticeMessageAccountEvent;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Eloquent\Message;
use App\Eloquent\MessageThread;
use App\Eloquent\MessageParticipant;

/**
 * Class MessageRepository.
 */
class MessageRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Message::class;
    }

    public function firstSendMessage($request) {
        DB::beginTransaction();
        try {
            $thread = MessageThread::create([
                'subject' => $request['subject'],
            ]);
            Message::create([
                'thread_id'  => $thread->id,
                'user_id'    => Auth::id(),
                'user_to_id' => $request['user_to'],
                'read_at'    => null,
                'body'       => $request['body'],
            ]);
            MessageParticipant::insert([
                [
                    'thread_id'  => $thread->id,
                    'user_id'    => Auth::id(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'thread_id'  => $thread->id,
                    'user_id'    => $request['user_to'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();

            return ['error' => $id_index];
        }

        return ['success' => $thread];
    }
}
