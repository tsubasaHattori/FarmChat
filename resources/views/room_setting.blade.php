@extends('layouts.app')
@section('title', 'FarmChat room-setting')

@section('style')
<link rel="stylesheet" href="{{ asset('css/room_setting.css') }}">
@endsection

@section('header_script')
<style>
</style>
@endsection

@section('content')
<div id="room-setting-container" class="container">
    <div class="title-block">
        <h2 class="page-title">ルーム設定</h3>
        <form v-if="canEdit" id="room-delete" method="POST" :action="'/room/setting/delete/' + room.id">
        @csrf
            <button type="submit" class="btn2 btn-negative" @click.prevent="deleteRoom()">ルーム削除</button>
        </form>
    </div>

    <form id="save" method="POST" :action="'/room/setting/' + room.id">
    @csrf
        <div class="form-item-block">
            <p>ルームID</p>
            <span>{{ $room['id'] }}</span>
        </div>

        <div v-if="room.type == 2" class="form-item-block">
            <p>ルームキー</p>
            <input type="text" name="room_key" :value="room.room_key" :disabled="!canEdit" required>
        </div>

        <div class="form-item-block">
            <p>ルーム名</p>
            <input type="text" name="room_name" :value="room.room_name" :disabled="!canEdit" required>
        </div>

        <div class="form-item-block">
            <p>開設者名</p>
            <span>@{{ room.owner.name }}</span>
        </div>

        <div class="form-item-block">
            <p>開設日</p>
            <span>@{{ formatDate(room.created_at) }}</span>
        </div>

        <div class="form-item-block">
            <p>詳細設定</p>
            <label><input type="checkbox" name="can_delete_message" v-model="room.can_delete_message" :disabled="!canEdit">メッセージの削除を可能にする</label>
        </div>

        <button v-if="canEdit" type="submit" class="btn2 btn-positive" @click.prevent="save">保存</button>
    </form>

</div>
@endsection

@section('footer_script')
<script type="text/javaScript">

var roomSetting = new Vue({
    el: "#room-setting-container",
    data: {
        room: @json($room),
        rooms: @json($rooms),
        selfUser: @json($self_user),
    },
    computed: {
        canEdit: function() {
            return this.room.owner_user_id == this.selfUser.id;
        },
    },
    methods: {
        formatDate: function(date) {
            return moment(date).format('YYYY/MM/DD');
        },

        save: function() {
            Swal.fire({
                title: '保存しています！',
                html : '',
                type : 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 800,
            }).then(function (result) {
                document.getElementById('save').submit();
            })
        },

        deleteRoom: function() {
            Swal.fire({
                title: 'ルームを削除しますか？',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: '削除',
                cancelButtonText : 'キャンセル',
                confirmButtonColor: '#d33',
                cancelButtonColor: '',
                reverseButtons : false,
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        title: '削除しました！',
                        showConfirmButton: true,
                        timer: 2000,
                    }).then(function() {
                        document.getElementById('room-delete').submit();
                    });
                }
            });
        },
    }
})
</script>
@endsection
