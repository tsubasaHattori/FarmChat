<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'room_name',
        'room_key',
        'is_deleted',
        'deleted_at',
        'owner_user_id',
        'type', // 1: public, 2: private, 3: test
    ];

    protected $dates = [
        'created_at', 'deleted_at',
    ];

    public function getTableName()
    {
        return 'rooms';
    }

    public function findByRoomId($room_id)
    {
        return $this
            ->select('*')
            ->from($this->getTableName())
            ->where('id', '=', $room_id)
            ->first()
            ->toArray();
    }

    public function findAiRoomByUserId($user_id)
    {
        return $this
            ->select('*')
            ->from($this->getTableName())
            ->where('owner_user_id', '=', $user_id)
            ->where('type', '=', 10)
            ->first()
            ->toArray();
    }

    // public function findRoomsByUserId($user_id)
    // {
    //     return $this
    //         ->select('*')
    //         ->from($this->getTableName())
    //         ->where('user_id', '=', $user_id)
    //         ->get()
    //         ->toArray();
    // }

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