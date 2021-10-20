<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'name',
        'user_id',
        'content',
        'is_deleted',
        'deleted_at',
        'reply_message_id',
        'is_edited',
        'room_id',
    ];

    protected $dates = [
        'created_at', 'deleted_at',
    ];

    public function getTableName()
    {
        return 'messages';
    }

    public function findByRoomId($room_id)
    {
        return $this
            ->select('*')
            ->from('messages')
            ->where('room_id', '=', $room_id)
            ->orderby('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function findExcludeDeactiveUsers()
    {
        return $this
            ->select('m.*')
            ->from('messages as m')
            ->join('users as u', 'm.user_id', '=', 'u.id')
            ->where('u.status', '!=', 9)
            ->orderby('m.created_at', 'asc')
            ->get()
            ->toArray();
    }
}
