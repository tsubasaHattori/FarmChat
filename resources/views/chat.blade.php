@extends('layouts.app')
@section('title', 'FarmChat Home')

@section('style')
<!-- <link rel="stylesheet" href="{{ asset('css/home.css') }}"> -->
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
<div id="home-container" class="container">
    <component-message-form
        :self-user = selfUser
        :room = room
        :initial-messages = initialMessages
        :initial-message-map = initialMessageMap
        :users = users
        @store="scrollEnd">
    </component-message-form>

    <button @click="scrollEnd" class="scroll-button"><i class="fa fa-arrow-down"></i>
</div>
@endsection

@section('footer_script')
<script type="text/javaScript">

var home = new Vue({
    el: "#home-container",
    data: {
        selfUser: @json($self_user),
        room: @json($room),
        initialMessages: @json($messages),
        initialMessageMap: @json($message_map),
        users: @json($users),
    },
    mounted: function() {
        this.$nextTick(function () {
            this.scrollEnd();
        });

    },
    methods: {
        scrollEnd: function() {
            var obj = document.getElementById("main");
            obj.scrollTo(0, obj.scrollHeight);
        },
    }
})
</script>
@endsection
