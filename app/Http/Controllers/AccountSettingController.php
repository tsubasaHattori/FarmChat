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


class AccountSettingController extends Controller
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

        return view('account_setting', [
            'rooms'      => $rooms,
            'self_user'  => $self_user,
        ]);
    }

    public function postAction(Request $req) {
        $self_user = Auth::user();

        $name = $req->get('name');
        $pronunciation = $req->get('pronunciation');

        $model = new User();
        $model->where('id', '=', $self_user['id'])->update([
            'name'               => $name,
            'name_pronunciation' => $pronunciation,
        ]);

        return redirect()->route('account.setting');
    }

    public function deleteAccount(Request $req) {
        $self_user = Auth::user();

        $model = new User();
        $model->where('id', '=', $self_user['id'])->update([
            'status' => 9,
        ]);

        Auth::logout();

        return redirect('/');
    }

}
