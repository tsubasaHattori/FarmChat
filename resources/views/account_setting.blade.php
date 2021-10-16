@extends('layouts.app')
@section('title', 'FarmChat setting')

@section('style')
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
<div id="account-setting-container" class="container">
    <div class="title-block">
        <h2 class="page-title">個人設定</h3>
        <!-- <button class="modal-open btn2 btn-positive create-room">新規ルーム作成</button> -->
    </div>

    <form method="POST">
    @csrf
        <div class="form-item-block">
            <p>名前</p>
            <input type="text" name="name" :value="selfUser.name" required>
        </div>
        <div class="form-item-block">
            <p>名前(フリガナ)</p>
            <input type="text" name="pronunciation" :value="selfUser.name_pronunciation" required>
        </div>

        <button type="submit" class="btn2 btn-positive">保存</button>
    </form>

</div>
@endsection

@section('footer_script')
<script type="text/javaScript">

var room = new Vue({
    el: "#account-setting-container",
    data: {
        selfUser: @json($self_user),
        rooms: @json($rooms),
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
