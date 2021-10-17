@extends('layouts.app')
@section('title', 'FarmChat setting')

@section('style')
@endsection

@section('header_script')
<style>
</style>
@endsection

@section('content')
<div id="account-setting-container" class="container">
    <div class="title-block">
        <h2 class="page-title">個人設定</h3>
        <form id="account-delete" method="POST" action="/account-setting/delete">
        @csrf
            <button type="submit" class="btn2 btn-negative" @click.prevent="deleteAccount()">アカウント削除</button>
        </form>
    </div>

    <form id="save" method="POST" action="/account-setting">
    @csrf
        <div class="form-item-block">
            <p>名前</p>
            <input type="text" name="name" :value="selfUser.name" required>
        </div>
        <div class="form-item-block">
            <p>名前(フリガナ)</p>
            <input type="text" name="pronunciation" :value="selfUser.name_pronunciation" required>
        </div>

        <button type="submit" class="btn2 btn-positive" @click.prevent="save">保存</button>
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
    methods: {
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

        deleteAccount: function() {
            Swal.fire({
                title: 'アカウントを削除しますか？',
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
                        document.getElementById('account-delete').submit();
                    });
                }
            });
        },
    }
})
</script>
@endsection
