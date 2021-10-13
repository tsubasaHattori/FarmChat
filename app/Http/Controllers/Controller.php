<?php

namespace App\Http\Controllers;

use Auth;
use App\Room;
use App\RoomUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUserRooms($user_id) {
        $rooms_raw = (new RoomUser())->findRoomsByUserId($user_id);

        $users = (new User())->get()->toArray();
        $user_map = array_column($users, null, 'id');

        foreach ($rooms_raw as $index => $room) {
            $rooms_raw[$index]['owner'] = $user_map[$room['owner_user_id']];
        }

        $rooms = [];

        $rooms['public'] = array_filter($rooms_raw, function($room) {
            return $room['type'] == 1;
        });

        $rooms['private'] = array_filter($rooms_raw, function($room) {
            return $room['type'] == 2;
        });

        return $rooms;
    }
}

