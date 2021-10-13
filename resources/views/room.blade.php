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
            <button class="btn2 btn-positive" type="submit">作成</button>
            <button class="modal-close btn2 btn-negative">キャンセル</button>
        </form>
    </div>

    <div class="title-block">
        <h2 class="page-title">ルーム一覧</h3>
        <button class="modal-open btn2 btn-positive create-room">新規ルーム作成</button>
        <hr>
    </div>

    <div style="text-align: right;">
    </div>

    <div class="public-room">
        <h2 class="sub-title">公開ルーム</h2>
        <div class="room-block">
            <ul class="room-list">
                <li v-for="(room, index) in rooms.public" :key="index" class="room-list-item">
                    <a :href="'/room/' + room.id">
                        <p class="room-name">@{{ room.room_name }}</p>
                        <p>開設者: @{{ room.owner.name }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="private-room">
        <h2 class="sub-title">プライベートルーム</h2>
        <div class="room-block">
            <ul class="room-list">
                <li v-for="(room, index) in rooms.private" :key="index" class="room-list-item">
                    <a :href="'/room/' + room.id">
                        <p class="room-name">@{{ room.room_name }}</p>
                        <p>開設者: @{{ room.owner.name }}</p>
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
                    <a href="{{ route('room-ai') }}">
                        <p class="room-name">AIチャットルーム</p>
                        <p>開設者: --</p>
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
        //     // ビュー全体がレンダリングされた後にのみ実行されるコード
            this.scrollEnd();
        });

    },
    methods: {
        scrollEnd: function() {
            var elementHtml = document.documentElement;
            var bottom = elementHtml.scrollHeight - elementHtml.clientHeight;
            window.scrollTo(0, bottom);
        },
    }
})
</script>
@endsection
