<?php

namespace App\Http\Controllers;

use App\Message;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DB;
use Verified;
use Carbon\Carbon;


class ChatController extends Controller
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

        $messages = (new Message())->findByRoomId($room_id);
        $message_map = array_column($messages, null, 'id');

        $users = (new User())->get()->toArray();
        $user_map = array_column($users, null, 'id');

        return view('chat', [
            'self_user'   => $self_user,
            'rooms'       => $rooms,
            'room'        => $room,
            'messages'    => $messages,
            'message_map' => $message_map,
            'users'       => $user_map,
        ]);
    }

    public function getAiRoom(Request $req) {
        $self_user = Auth::user();
        $rooms = $this->getUserRooms($self_user['id']);

        $ai_room = (new Room())->findAiRoomByUserId($self_user['id']);

        $messages = (new Message())->findByRoomId($ai_room['id']);
        $message_map = array_column($messages, null, 'id');

        $users = (new User())->get()->toArray();
        $user_map = array_column($users, null, 'id');

        return view('chat', [
            'self_user'   => $self_user,
            'rooms'       => $rooms,
            'room'        => $ai_room,
            'messages'    => $messages,
            'message_map' => $message_map,
            'users'       => $user_map,
        ]);
    }


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

    public function getMessages(Request $req) {
        $room_id = $req->room_id;

        $model = new Message();
        $messages = $model->findByRoomId($room_id);
        $message_map = array_column($messages, null, 'id');

        return [
            'messages' => $messages,
            'message_map' => $message_map,
        ];
    }

    public function store(Request $req) {
        $user_id = $req->get('user_id');
        $user_name = $req->get('user_name');
        $content = $req->get('content');
        $reply_message_id = $req->get('reply_message_id');
        $room_id = $req->room_id;
        $room_type = $req->room_type;

        $model = new Message();
        $max_id = $model::max('id');

        $model->insert([
            'id'               => $max_id + 1,
            'user_id'          => $user_id,
            'name'             => $user_name,
            'content'          => $content,
            'reply_message_id' => $reply_message_id,
            'room_id'          => $room_id,
        ]);

        // Aiチャットルーム自動返信
        if ($room_type == 10) {
            $folder_path = config('services.python_path');
            preg_match('/train:@(\w+)/', $content, $match);
            if (isset($match[1])) {
                $exec_file = 'twitter_gen_text.py';
                $params = $match[1];
                foreach (config('services.twitter') as $value) {
                    $params .= " ${value}";
                }
                $command = "python3 ${folder_path}${exec_file} ${params};";
                exec($command, $output, $result_code);
                $reply_content = $output[1];
            } else {
                $exec_file = 'chaplus_reply.py';
                $params = $user_name . " " . $content . " " . config('services.chaplus.api_key');
                $command = "python3 ${folder_path}${exec_file} ${params};";
                exec($command, $output, $result_code);
                $reply_content = $output[0];
            }

            $max_id = $model::max('id');
            $model->insert([
                'id'               => $max_id + 1,
                'user_id'          => 3,
                'name'             => 'AIチャット',
                'content'          => $reply_content,
                'reply_message_id' => null,
                'room_id'          => $room_id,
            ]);
        }

        return [
            'id'               => $max_id + 1,
            'user_id'          => $user_id,
            'user_name'        => $user_name,
            'content'          => $content,
            'reply_message_id' => $reply_message_id,
            'room_id'          => $room_id,
        ];
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
