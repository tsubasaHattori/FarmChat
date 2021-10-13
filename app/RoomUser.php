<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomUser extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'is_admin',
        'is_deleted',
        'deleted_at',
    ];

    protected $dates = [
        'created_at', 'deleted_at',
    ];

    public function getTableName()
    {
        return 'room_users';
    }

    public function findRoomsByUserId($user_id)
    {
        return $this
            ->select('r.*')
            ->from('room_users as ru')
            ->join('rooms as r', 'ru.room_id', '=', 'r.id')
            ->where('ru.user_id', '=', $user_id)
            ->where('r.type', '=', 2)
            ->orWhere('r.type', '=', 1)
            ->get()
            ->toArray();
    }

    // public function deleteMessage($message_id)
    // {
    //     $now = Carbon::now();

    //     $this->from('messages as m')
    //         ->where('m.id', $message_id)
    //         ->update([
    //             'is_deleted' => true,
    //             'deleted_at' => $now,
    //         ]);
    // }
}
