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

    // public function getAction(Request $req) {
    //     $self_user = Auth::user();

    //     $room_id = $req->room_id;

    //     $messages = (new Message())->findByRoomId($room_id);
    //     $message_map = array_column($messages, null, 'id');

    //     $users = (new User())->get()->toArray();
    //     $user_map = array_column($users, null, 'id');

    //     return view('chat', [
    //         'room_id'    => $room_id,
    //         'messages'   => $messages,
    //         'messageMap' => $message_map,
    //         'users'      => $user_map,
    //         'auth_user'  => $self_user,
    //     ]);
    // }

    // public function store(Request $req) {
    //     $user = json_decode(Auth::user(), true);

    //     $content = $req->content;

    //     DB::table('messages')->insert([
    //         'user_id' => $user['id'],
    //         'name'    => $user['name'],
    //         'content' => $content,
    //     ]);

    //     return redirect('home');
    // }

    // public function delete(Request $req) {
    //     $message_id = $req->id;

    //     DB::table('messages')->where('id', '=', $message_id)->update([
    //         'is_deleted' => true,
    //         'deleted_at' => Carbon::now(),
    //     ]);

    //     return redirect('home');
    // }



    public function store(Request $req) {
        $self_user = Auth::user();

        $room_name = $req->get('room_name');
        $room_type = $req->get('room_type') == "public" ? 1 : 2;
        $room_key = $req->get('room_key');

        // var_dump($room_name);
        // var_dump($room_type);
        // var_dump($room_key);die;

        $Room = new Room();
        $room_max_id = $Room::max('id');

        $Room->insert([
            'id'            => $room_max_id + 1,
            'room_name'     => $room_name,
            'type'          => $room_type,
            'room_key'      => $room_key,
            'owner_user_id' => $self_user['id'],
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

    public function destroy(Request $req) {
        $message_id = $req->message_id;
        $content = $req->get('content');

        $model = new Message();
        $model->deleteMessage($message_id);
        // $model->destroy($message_id);

        return [
            'message_id' => $message_id,
            'content'    => $content,
        ];
    }

    public function edit(Request $req) {
        $message_id = $req->get('message_id');
        $content = $req->get('content');
        $reply_message_id = $req->get('reply_message_id');

        $model = new Message();
        $targetMessage = $model->find($message_id);

        $targetMessage->update([
            'content'          => $content,
            'reply_message_id' => $reply_message_id,
            'is_edited'        => true,
        ]);

        return [
            'content'          => $content,
            'reply_message_id' => $reply_message_id,
            'is_edited'        => true,
        ];
    }
}
