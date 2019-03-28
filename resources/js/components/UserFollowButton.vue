<template>
    <button type="button" class="btn btn-primary" @click="follow" :hidden="state" :class="{'btn-danger':followed}" v-text="text"></button>
</template>

<script>
    export default {
        props:['followeds'],
        mounted() {
            axios.post('/api/users/is_follow', {'follow':this.followeds}).then(response => {
                this.followed = response.data.followed;
            })
        },
        data(){
            return {
                followed: '',
                state: true,
            }
        },
        computed: {
            text(){
                return this.followed ? '取消关注' : '关注作者'
            }
        },
        methods:{
            follow(){
                axios.post('/api/users/follow', {'follow':this.followeds}).then(response => {
                    this.followed = !! response.data.followed.attached.length;
                });
            }
        },
        updated(){
            this.state = false;
        }
    }
</script>
