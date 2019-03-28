<template>
    <div class="col-md-6">
        <button type="button" class="btn btn-link" data-toggle="modal"
                :data-target="btn_dialog" @click="getComments">{{ count }} 条评论
        </button>
        <div class="modal fade" :id="dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">评论列表</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-3" v-for="comment in comments">
                            <div class="media border p-3">
                                <img :src="comment.user.avatar" class="mr-3 mt-3 rounded-circle" style="width:60px;">
                                <div class="media-body">
                                    <h4>{{comment.user.name}}</h4>
                                    <p class="new-line">{{comment.body}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="container mt-3">
                            <div class="alert alert-success" v-if="status">
                                <strong>发送成功</strong>
                            </div>
                        </div>
                    </div>
                    <form>
                        <div class="modal-footer ">
                            <input type="text" name="body" class="form-control col-md-9" id="" v-model="body">
                            <button type="button" class="btn btn-primary col-md-2" @click="createComment"
                                    :disabled="dis">
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
        props: ['tid', 'type', 'count'],
        data(){
            return {
                id: 0,
                body: '',
                dis: false,
                status: false,
                comments: []
            }
        },
        computed: {
            dialog(){
                return this.type + this.tid;
            },
            btn_dialog(){
                return '#' + this.type + this.tid;
            }
        },
        methods: {
            getComments(){
                axios.get('/api/comments/' + this.tid + '/' + this.type).then(response => {
                    this.comments = response.data;
                })
            },
            createComment(){
                this.dis = 'disabled';
                axios.post('/api/comments', {
                    'id': this.tid,
                    'body': this.body,
                    'type': this.type
                }).then(response => {
                    this.getCommentsCount();
                    this.getComments();
                    this.status = true;
                    this.body = '';
                    this.dis = false;
                });
            },
            getCommentsCount(){
                axios.get('/api/comments/count/' + this.type + '/' + this.tid).then(response => {
                    this.count = response.data.count;
                })
            },
        },
    }
</script>
