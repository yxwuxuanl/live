var
    vm;

vm = new Vue({
    el : '#app',

    data : {
        port : '',
        socketReady : false,
        uploading : false,
        asset : {},
        text : '',
        pushing : false
    },

    created : function(){
        var
            vm = this;

        axios.get('../config.json').then(function(response){
            vm.initSocket(response.data.backendWsHost, response.data.backendPort);
        });

    },

    methods : {
        initSocket : function(host, port){
            var
                socket = new WebSocket(host + ':' + port),
                vm = this;

            socket.onopen = function(){
                vm.$soctek = socket;
                vm.socketReady = true;
            }

            socket.onmessage = function(event){
                var
                    data = JSON.parse(event.data);

                if(data.confirm){
                    vm.pushFinish();
                }
            }
        },
        
        upload : function(){
            var
                vm = this;

            if(this.text){
                axios.post('./uploadText.php', 'text=' + this.text).then(function(response){
                    vm.push(response.data);
                });
            }else{
                vm.push();
            }
        },

        push : function(text){
            var
                date = new Date(),
                time = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds(),
                asset = {},
                vm = this;

            asset.time = time;
            asset.content = [];

            if(text){
                asset.content.push(text);
            }

            if('type' in this.asset){
                asset.content.push(this.asset);
            }

            this.$soctek.send(JSON.stringify(asset));
            this.pushing = true;
        },

        pushFinish : function(){
            this.$Message.success('发布完成!');
            this.reset();
        },

        uploadAssetSuccess : function(response){
            this.uploading = false;

            if(response.status === 'success')
            {
                this.asset = response;
            }else{
                this.$Message.error({
                    content : '上传失败,请刷新',
                    duration : 0
                });
            }
        },

        beforeUpload : function(){
            this.uploading = true;
        },

        reset : function(){
            this.text = '';
            this.asset = {};
            this.$refs.upload.clearFiles();
            this.pushing = false;
        }
    }
});