<template>
    <div class="col-md-6 text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal"
                data-target="#message" :disabled="dis">{{ btn_text }}
        </button>
        <div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">发送私信</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="alert alert-success" v-if="status">
                                    <strong>私信发送成功</strong>
                                </div>
                                <textarea class="form-control" id="message-text" v-model="content"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">关闭
                            </button>
                            <button type="button" class="btn btn-primary" @click="sendMessage" :disabled="send_dis">
                                发送
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['to_user_id'],
        data(){
            return {
                dis: false,
                send_dis: false,
                btn_text: '发送信息',
                content: '',
                status: false,
            }
        },
        methods: {
            sendMessage(){
                if (this.content == '') {
                    alert('请填写内容');
                    return;
                }
                this.send_dis = 'disabled';
                axios.post('/api/send_message', {
                    'to_user_id': this.to_user_id,
                    'body': this.content
                }).then(response => {
                    if (response.status === 200) {
                        this.status = true;
                        setTimeout(function () {
                            $('#message').modal('hide');
                            $('.modal-backdrop').remove();
                        }, 750);
                    }
                    this.btn_text = '已发送';
                    this.dis = 'disabled';
                })
            }
        },
    }
</script>
