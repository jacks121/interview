<template>
    <div class="text-center">
        <my-upload field="img"
                   :width="300"
                   :height="300"
                   url="/avatar"
                   :params="params"
                   :headers="headers"
                   :value.sync="show"
                   :no-rotate="false"
                   @crop-success="cropSuccess"
                   @crop-upload-success="cropUploadSuccess"
                   @crop-upload-fail="cropUploadFail"
                   img-format="png"></my-upload>
        <img :src="imgDataUrl" height="100">
        <div class="mt-1">
            <button class="btn btn-primary" @click="toggleShow">设置头像</button>
        </div>
    </div>
</template>

<script>
    import 'babel-polyfill'; // es6 shim
    import myUpload from 'vue-image-crop-upload';
    export default {
        props: ['user_avatar'],
        data() {
            return {
                show: false,
                params: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: 'avatar'
                },
                headers: {
                    smail: '*_~'
                },
                imgDataUrl: this.user_avatar
            }
        },
        components: {
            'my-upload': myUpload
        },
        methods: {
            toggleShow() {
                this.show = !this.show;
            },
            cropSuccess(data, field, key) {
                if (field == 'img') {
                    this.imgDataUrl = data;
                }
                console.log('-------- 剪裁成功 --------');
            },
            cropUploadSuccess(data, field, key) {
                console.log('-------- 上传成功 --------');
                console.log(data);
                console.log('field: ' + field);
                console.log('key: ' + key);
            },
            cropUploadFail(status, field, key) {
                console.log('-------- 上传失败 --------');
                console.log(status);
                console.log('field: ' + field);
                console.log('key: ' + key);
            }
        },
    }
</script>
