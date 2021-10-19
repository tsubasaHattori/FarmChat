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

    public function findByRoomIdUserId($room_id, $user_id)
    {
        return $this
            ->select('*')
            ->from($this->getTableName())
            ->where('room_id', '=', $room_id)
            ->where('user_id', '=', $user_id)
            ->first();
    }

    public function findRoomsByUserId($user_id)
    {
        return $this
            ->select('r.*')
            ->from('room_users as ru')
            ->join('rooms as r', 'ru.room_id', '=', 'r.id')
            ->where('r.is_deleted', '=', false)
            ->where(function($query) use($user_id) {
                $query
                    ->where('r.type', '=', 2)
                    ->where('ru.user_id', '=', $user_id)
                    ->orWhere('r.type', '=', 1);
            })
            ->get()
            ->toArray();
    }

}
