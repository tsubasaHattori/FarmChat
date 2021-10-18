@extends('layouts.app')
@section('title', 'FarmChat Room')

@section('style')
<link rel="stylesheet" href="{{ asset('css/room.css') }}">
@endsection

@section('header_script')
<style>
    .scroll-button {
        position: fixed;
        right: 20px;
        bottom: 60px;
        opacity: 0.7;
    }
</style>
@endsection

@section('content')
<div id="room-container" class="container">

    <div id="overlay" class="overlay"></div>
    <div class="modal-window">
        <form method="POST" action="/room/store" name="room-create-form" >
        @csrf
            <h2 class="head-title">新規ルーム作成</h3>
            <div class="form-item-block">
                <p>ルーム名</p>
                <input type="text" name="room_name" value="" required>
            </div>
            <div class="form-item-block">
                <p>ルームタイプ</p>
                <label><input type="radio" name="room_type" value="public" v-model="createRoomType" required> 公開ルーム</label>
                <label><input type="radio" name="room_type" value="private" v-model="createRoomType" required> プライベートルーム</label>
            </div>
            <div v-if="createRoomType == 'private'" class="form-item-block">
                <p>ルームキー</p>
                <input type="text" name="room_key" value="" required>
            </div>
            <div class="form-item-block">
                <p>詳細設定</p>
                <label><input type="checkbox" name="can_delete_message" checked>メッセージの削除を可能にする</label>
            </div>
            <button class="btn2 btn-positive" type="submit">作成</button>
            <button class="modal-close btn2 btn-negative">キャンセル</button>
        </form>
    </div>

    <div class="title-block">
        <h2 class="page-title">ルーム一覧</h3>
        <button class="modal-open btn2 btn-positive create-room">新規ルーム作成</button>
    </div>

    <div style="text-align: right;">
    </div>

    <div v-if="Object.keys(rooms.public).length" class="public-room">
        <h2 class="sub-title">公開ルーム</h2>
        <div class="room-block">
            <ul class="room-list">
                <li v-for="(room, index) in rooms.public" :key="index" class="room-list-item">
                    <a :href="'/room/' + room.id" class="room-box">
                        <p>
                            <span class="room-name">@{{ room.room_name }}</span>
                            <object><a :href="'/room/setting/' + room.id" class="room-setting">[ ルーム設定 ]</a></object>
                        </p>
                        <p class="room-description">
                            <span>開設者: @{{ room.owner.name }}</span>
                            <span>開設日: @{{ formatDate(room.created_at) }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div v-if="Object.keys(rooms.private).length" class="private-room">
        <h2 class="sub-title">プライベートルーム</h2>
        <div class="room-block">
            <ul class="room-list">
                <li v-for="(room, index) in rooms.private" :key="index" class="room-list-item">
                    <a :href="'/room/' + room.id" class="room-box">
                        <p>
                            <span class="room-name">@{{ room.room_name }}</span>
                            <i class="fas fa-key" style="font-size: 14px;"></i>
                            <object><a :href="'/room/setting/' + room.id" class="room-setting">[ ルーム設定 ]</a></object>
                        </p>
                        <p class="room-description">
                            <span>開設者: @{{ room.owner.name }}</span>
                            <span>開設日: @{{ formatDate(room.created_at) }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="special-room">
        <h2 class="sub-title">特別ルーム</h2>
        <div class="room-block">
            <ul class="room-list">
                <li class="room-list-item">
                    <a href="{{ route('room.ai') }}" class="room-box">
                        <p class="room-name">AIチャットルーム</p>
                        <p class="room-description">
                            <span>開設者: --</span>
                            <span>開設日: --</span>
                        </p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <button @click="scrollEnd" class="scroll-button"><i class="fa fa-arrow-down"></i>
</div>
@endsection

@section('footer_script')
<script type="text/javaScript">

$(function () {

// modal
$('.modal-open').click(function () {
    $('#overlay, .modal-window').show();
});

$('.modal-close, #overlay').click(function () {
    $('#overlay, .modal-window').hide();
});

});

var room = new Vue({
    el: "#room-container",
    data: {
        selfUser: @json($self_user),
        rooms: @json($rooms),
        createRoomType: 'public',
    },
    mounted: function() {
        this.$nextTick(function () {
            this.scrollEnd();
        });

    },
    methods: {
        formatDate: function(date) {
            return moment(date).format('YYYY/MM/DD');
        },

        scrollEnd: function() {
            var elementHtml = document.documentElement;
            var bottom = elementHtml.scrollHeight - elementHtml.clientHeight;
            window.scrollTo(0, bottom);
        },
    },
})
</script>
@endsection
