<template>
    <button type="button" class="btn btn-primary" @click="follow" :hidden="state" :class="{'btn-danger':followed}" v-text="text"></button>
</template>

<script>
    export default {
        props:['question'],
        mounted() {
            axios.post('/api/questions/is_follow', {'question':this.question}).then(response => {
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
                return this.followed ? '取消关注' : '关注该问题'
            }
        },
        methods:{
            follow(){
                axios.post('/api/questions/follow', {'question':this.question}).then(response => {
                    this.followed = !! response.data.followed.attached.length;
                });
            }
        },
        updated(){
            this.state = false;
        }
    }
</script>
