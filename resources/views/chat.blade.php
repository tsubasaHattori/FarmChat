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
        :initial-messages = messages
        :initial-message-map = messageMap
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
        messages: @json($messages),
        messageMap: @json($message_map),
        users: @json($users),
    },
    mounted: function() {
        this.$nextTick(function () {
            this.scrollEnd();
        });

    },
    methods: {
        scrollEnd: function() {
            var elementHtml = document.documentElement;
            var bottom = elementHtml.scrollHeight - elementHtml.clientHeight;
            console.log(bottom);
            console.log(elementHtml.scrollHeight);
            console.log(elementHtml.clientHeight);
            window.scrollTo(0, bottom);
        },
    }
})
</script>
@endsection
