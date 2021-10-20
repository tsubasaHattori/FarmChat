<style>
.day-change-block {
    text-align: center;
    margin-top: 35px;
}

.day-change {
    display: inline-block;
    width: 100px;
    font-size: 12px;
    border-radius: 8px;
    background: #8EB8FF;
    box-shadow: 0px
     0px 0px 5px #8EB8FF;
}

.write-block {
    text-align: center;
    margin-bottom: 10px;
}

.message-form-label {
    font-size: 25px;
    background: linear-gradient(transparent 70%, #a7d6ff 70%);
}

.message-form-label-edit {
    margin-left: 65px;
}

.edit-cancel {
    color: rgba(0, 0, 0, 0.7);
    text-decoration: underline;
    margin-left: 15px;
    cursor: pointer;
}

.message {
    margin-top: 30px;
}

.my-message {
    text-align: right;
}

.writer .fa{
    margin-right: 10px;
}

.time {
    font-size: 12px;
    opacity: 0.6;
}

.is-edited {
    font-size: 12px;
    opacity: 0.6;
}

.fa-trash {
    cursor: pointer;
    color: #CC3333;
}

.fa-remove {
    cursor: pointer;
    color: #CC3333;
}

.fa-comment {
    color: #20B2AA;
    cursor: pointer;
}

.fa-edit {
    cursor: pointer;
}

.reply-content {
    font-size: 8px;
    white-space: pre-wrap;
    cursor: text;
}

.my-message .reply-line .content-line {
    width: 50%;
    text-align: right;
    margin: 0.1em 0 0.1em auto;
}

.my-message .reply-line .content-box {
    background: #D7EEFF;
    box-shadow: 0px 0px 0px 5px #D7EEFF;
    text-align: left;
}

.others-message .reply-line .content-line {
    width: 50%;
    margin: 0.1em 0 0.1em 0;
}

.others-message .reply-line .content-box {
    background: #D7EEFF;
    box-shadow: 0px 0px 0px 5px #D7EEFF;
}

.reply-line .reply-content-box-null {
    background: #DDDDDD;
    box-shadow: 0px 0px 0px 5px #DDDDDD;
}

.reply-line .writer {
    font-size: 10px;
}

.my-message .content-line {
    width: 70%;
    text-align: right;
    margin: 1em 0 1em auto;
}

.my-message .content-box {
    background: #66FF99;
    box-shadow: 0px 0px 0px 9px #66FF99;
    text-align: left;
}

.others-message .content-line {
    width: 70%;
    margin: 1em 0 1em 0;
}

.others-message .content-box {
    background: #ffeaea;
    box-shadow: 0px 0px 0px 9px #ffeaea;
}

.content-box, .content-box-null {
    color: #565656;
    display: inline-block;
    border-radius: 8px;
}

.content-box-null {
    background: #DDDDDD;
    box-shadow: 0px 0px 0px 9px #DDDDDD;
}

.content {
    white-space: pre-wrap;
    cursor: text;
}

.store-form {
    width: 500px;
}

.reply-preview {
    margin-left: 20%;
}

@media screen and (max-width: 560px) {
    .store-form {
        width: 90%;
    }

    .reply-preview {
        margin-left: 0;
    }
}

@media screen and (max-width: 960px) {
    store-form {
        width: 80%;
    }

    .reply-preview {
        margin-left: 0;
    }
}

textarea {
    font-size: 16px;
}

.btn-square-shadow {
    display: inline-block;
    padding: 0.5em 1em;
    text-decoration: none;
    background: #fd9535;/*ボタン色*/
    color: #FFF;
    border-bottom: solid 4px #627295;
    border-radius: 3px;
}
.btn-square-shadow:active {
    /*ボタンを押したとき*/
    -webkit-transform: translateY(4px);
    transform: translateY(4px);/*下に動く*/
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
    border-bottom: none;
}
</style>

<template>
<div>
    <div v-for="(message, index) in messages" :key="index" class="messages-block">
        <form>
            <div class="day-change-line">
                <div v-if="index == 0" class="day-change-block">
                    <span class="day-change">{{ message.created_at | moment("M/D (ddd)") }}</span>
                </div>
                <div v-if="index-1 > 0 && $moment(message.created_at).date() - $moment(messages[index-1].created_at).date() > 0" class="day-change-block">
                    <span class="day-change">{{ message.created_at | moment("M/D (ddd)") }}</span>
                </div>
            </div>
            <div class="message" :class='[message.user_id == selfUser.id ? "my-message" : "others-message"]'>
                <div class="upper-line">
                    <span class="writer">
                    <i v-if="!message.is_deleted && message.user_id == selfUser.id && room.can_delete_message" id="trash" class="fa fa-trash fa-lg" @click="deleteMessage(message)"></i>
                    <i v-if="!message.is_deleted && message.user_id == selfUser.id" class="fa fa-comment fa-lg" style="margin-right: 3px;" @click="reply(message.id)"></i>
                    {{ users[message.user_id].name }}
                    <i v-if="!message.is_deleted && message.user_id != selfUser.id" class="fa fa-comment fa-lg" style="margin-left: 4px;" @click="reply(message.id)"></i>
                    </span>
                </div>
                <div class="middle-line">
                    <i v-if="!message.is_deleted && message.user_id == selfUser.id" class="fa fa-edit" @click="edit(message)"></i>
                    <span v-if="message.is_edited && message.user_id == selfUser.id" class="is-edited">(編集済み)</span>
                    <span class="time">{{ message.created_at | moment("HH:mm") }}</span>
                    <span v-if="message.is_edited && message.user_id != selfUser.id" class="is-edited">(編集済み)</span>
                </div>
                <div class="content-line">
                    <div v-if="message.is_deleted">
                        <span class="content-box-null">
                            <span class="content">メッセージが削除されました</span>
                        </span>
                    </div>
                    <div v-else>
                        <span class="content-box">
                            <span class="content">{{ message['content'] }}</span>
                        </span>
                    </div>
                </div>
                <div v-if="message.reply_message_id && !message.is_deleted && messageMap[message.reply_message_id]" class="reply-line">
                    <div class="upper-line">
                        <span class="writer">
                            <i v-if="message.user_id != selfUser.id" class="fa fa-reply fa-rotate-180" style="margin-right: 0px" aria-hidden="true"></i>
                            {{ messageMap[message.reply_message_id].name }}
                            <i v-if="message.user_id == selfUser.id" class="fa fa-reply fa-flip-vertical" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="content-line reply-content-line">
                        <div v-if="messageMap[message.reply_message_id].is_deleted">
                            <span class="content-box-null reply-content-box-null">
                                <span class="reply-content">メッセージが削除されました</span>
                            </span>
                        </div>
                        <div v-else>
                            <span class="content-box reply-content-box">
                                <span class="content reply-content">{{ messageMap[message.reply_message_id].content }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="message-form">
        <hr width = "100%">
        <div v-if="!isEditing" class="write-block">
            <span class="message-form-label">書き込み</span>
        </div>
        <div v-else class="write-block">
            <span class="message-form-label message-form-label-edit">メッセージ編集</span>
            <span class="edit-cancel" @click="cancelEdit">取り消し</span>
        </div>
        <div v-if="replyMessageId" class="reply-block">
            <div class="reply-preview">
                <div class="message others-message" style="margin-top: 0">
                <span><i class="fa fa-remove fa-lg" @click="replyMessageId=null"></i> <span style="font-weight: bold;">リプライ : </span> {{ messageMap[replyMessageId].name }}</span>
                    <div class="content-line">
                        <span v-if="!messageMap[replyMessageId].is_deleted" class="content-box-null">
                            <span class="content">{{ messageMap[replyMessageId].content }}</span>
                        </span>
                        <span v-else class="content-box-null">
                            <span class="content">メッセージが削除されました</span>
                        </span>
                    </div>
                </div>
            </div>
        </div><center>
        <div class="store-form">
            内容<br>
            <textarea v-model="content" name="content" style="width: 100%; height: 80px;" required></textarea><br>
            <input v-if="!isEditing" @click="sendMessage" class="btn btn-square-shadow" type="submit" value="投稿" :disabled="isPosting || !content">
            <input v-else @click="editMessage" class="btn btn-square-shadow" type="submit" value="修正" :disabled="isPosting || !content">
        </div>
        <hr width = "100%"></center>
    </div>
</div>
</template>

<script>
    export default {
        delimiters: ['${','}'],
        props: [
            'selfUser',
            'room',
            'initialMessages',
            'initialMessageMap',
            'users',
        ],
        data: function () {
            return {
                content: "",
                isEditing: false,
                isPosting: false,
                messages: this.initialMessages,
                messageMap: this.initialMessageMap,
                replyMessageId: null,
                editMessageId: null,
            }
        },
        created() {
            Echo.channel('chatbox')
                .listen('SendMessage', (e) => {
                    console.log('listen!');
                    console.log(e.message);
                });
        },
        mounted: function() {
            this.getMessages();
            setInterval(this.getMessages, 3000);
        },
        methods: {
            getMessages: function(scroll = false) {
                var self = this;
                var url = '/api/message/get/' + this.room.id;
                axios.get(url)
                .then((res)=>{
                    this.messages = res.data.messages;
                    this.messageMap = res.data.message_map;

                    if (scroll) {
                        setTimeout(function() {
                            self.$emit('store');
                        }, 0);
                    }
                })
                .catch(error => console.log(error))
            },

            storeMessage: function() {
                this.isPosting = true;

                var url = '/api/message/store';
                axios.post(url, {
                    user_id: this.selfUser.id,
                    user_name: this.selfUser.name,
                    content: this.content,
                    reply_message_id : this.replyMessageId,
                    room_id: this.room.id,
                    room_type: this.room.type,
                })
                .then((res)=>{
                    this.isPosting = false;
                    this.content = "";
                    this.replyMessageId = null,
                    this.getMessages(true);
                })
                .catch(error => console.log(error))
            },

            deleteMessage: function(message) {
                if (!window.confirm('本当に削除しますか？')) {
                    return false;
                }

                var url = '/api/message/delete/' + message.id;
                axios.post(url, {
                    content: message.content,
                })
                .then((res)=>{
                    this.getMessages();
                })
                .catch(error => console.log(error))

                return true;
            },

            editMessage: function() {
                this.isPosting = true;

                var url = '/api/message/edit';
                axios.post(url, {
                    message_id: this.editMessageId,
                    content: this.content,
                    reply_message_id : this.replyMessageId,
                })
                .then((res)=>{
                    this.isPosting = false;
                    this.isEditing = false;
                    this.content = "";
                    this.editMessageId = null;
                    this.replyMessageId = null;
                    this.getMessages(true);
                })
                .catch(error => console.log(error))
            },

            edit: function(message) {
                var self = this;

                this.isEditing = true;
                this.replyMessageId = null;
                this.editMessageId = message.id;
                this.content = message.content;
                if (message.reply_message_id) {
                    this.reply(message.reply_message_id);
                } else {
                    setTimeout(function() {
                        self.$emit('store');
                    }, 0);
                }
            },

            cancelEdit: function() {
                this.isEditing = false;
                this.content = "";
                this.editMessageId = null;
                this.replyMessageId = null;
            },

            reply: function(reply_message_id) {
                this.replyMessageId = reply_message_id;
                this.$emit('store');
            },

            sendMessage: function() {
                axios.post('/api/message/index', {message: 'gaogao_message'});
            },
        },
    }
</script>
