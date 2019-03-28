<template>
    <div class="">
        <div class="media" v-for="message in messages">
            <img height="50" class="img-border mt-2 mr-2" :src="message.from_user.avatar"
                 alt="">
            <div class="media-body mt-3">
                <h5>发信人：{{message.from_user.name}}</h5>
                信件内容：{{message.body}} <span
                    class="float-right">{{message.created_at}}</span>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-block" @click="getMessage" v-text="btn_text" :disabled="dis"></button>
    </div>
</template>

<script>
    export default {
        props: ['dialog_id'],
        mounted() {
            axios.get('/api/messages/' + this.dialog_id + '/' + this.offset + '/' + this.limit).then(response => {
                this.messages = response.data;
                if (response.data.length < 10) {
                    this.state = 'none';
                }
            });
        },
        data(){
            return {
                offset: 0,
                limit: 10,
                state: true,
                messages: []
            }
        },
        computed: {
            btn_text(){
                if (this.state === 'none') {
                    return '没有更多消息了';
                }
                return this.state ? '加载更多' : '加载中...'
            },
            dis(){
                return this.state ? false : 'disabled'
            }
        },
        methods: {
            getMessage(){
                this.offset += 10;
                this.state = false;
                axios.get('/api/messages/' + this.dialog_id + '/' + this.offset + '/' + this.limit).then(response => {
                    if (response.data.length >= 10) {
                        this.messages = this.messages.concat(response.data);
                        this.state = true;
                    } else {
                        this.state  = 'none';
                    }

                });
            },
        }
    }
</script>
