<?php

namespace App\Http\Controllers;

use App\Message;
use App\Room;
use App\RoomUser;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DB;
use Verified;
use Carbon\Carbon;


class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getListAction(Request $req) {
        $self_user = Auth::user();
        $rooms = $this->getUserRooms($self_user['id']);

        return view('room', [
            'rooms'      => $rooms,
            'self_user'  => $self_user,
        ]);
    }

    public function store(Request $req) {
        $self_user = Auth::user();

        $room_name = $req->get('room_name');
        $room_type = $req->get('room_type') == 'public' ? 1 : 2;
        $room_key = $req->get('room_key');
        $can_delete_message = $req->get('can_delete_message') == 'on' ? true : false;

        $Room = new Room();
        $room_max_id = $Room::max('id');

        $Room->insert([
            'id'                 => $room_max_id + 1,
            'room_name'          => $room_name,
            'type'               => $room_type,
            'room_key'           => $room_key,
            'can_delete_message' => $can_delete_message,
            'owner_user_id'      => $self_user['id'],
        ]);

        if ($room_type) {
            $RoomUser = new RoomUser();
            $max_id = $RoomUser::max('id');

            $RoomUser->insert([
                'id'            => $max_id + 1,
                'room_id'       => $room_max_id + 1,
                'user_id'       => $self_user['id'],
                'is_admin'      => true,
            ]);
        }

        return redirect('room');
    }

    public function search(Request $req) {
        $self_user = Auth::user();

        $room_id = $req->room_id;
        $room_key = $req->room_key;

        $room = (new Room())->findByRoomIdRoomKey($room_id, $room_key);
        if (!$room) {
            return [
                'is_completed' => false,
                'error_message' => 'ルームが存在しないか、ルームキーが違います',
            ];
        }

        $RoomUser = new RoomUser();
        $exist_room = $RoomUser->findByRoomIdUserId($room_id, $self_user['id']);
        if ($exist_room) {
            return [
                'is_completed' => false,
                'error_message' => '入室済みのプライベートルームです',
            ];
        }

        $max_id = $RoomUser::max('id');
        $RoomUser->insert([
            'id'            => $max_id + 1,
            'room_id'       => $room_id,
            'user_id'       => $self_user['id'],
            'is_admin'      => false,
        ]);

        return [
            'is_completed' => true,
        ];
    }
}
