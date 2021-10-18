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
use DateTime;
use Carbon\Carbon;


class RoomSettingController extends Controller
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
    public function getAction(Request $req) {
        $self_user = Auth::user();
        $rooms = $this->getUserRooms($self_user['id']);

        $room_id = $req->room_id;
        $room = (new Room())->findByRoomId($room_id);

        $room_owner = (new User())->findByUserId($room['owner_user_id']);
        $room['owner'] = $room_owner;

        return view('room_setting', [
            'room'      => $room,
            'rooms'     => $rooms,
            'self_user' => $self_user,
        ]);
    }

    public function postAction(Request $req) {
        $self_user = Auth::user();

        $room_id = $req->room_id;
        $room_key = $req->room_key;
        $room_name = $req->room_name;
        $can_delete_message = $req->can_delete_message;

        $model = new Room();
        $model->where('id', '=', $room_id)->update([
            'room_key'           => $room_key,
            'room_name'          => $room_name,
            'can_delete_message' => $can_delete_message == 'on' ? true : false,
        ]);

        return redirect()->route('room', [
            'room_id' => $room_id
        ]);
    }

    public function deleteRoom(Request $req) {
        $self_user = Auth::user();

        $room_id = $req->room_id;

        $model = new Room();
        $model->where('id', '=', $room_id)->update([
            'is_deleted' => true,
            'deleted_at' => Carbon::now(),
        ]);

        return redirect()->route('room');
    }

}
