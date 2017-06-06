var
    vm = new Vue({
        el : '#app',
        data : {
            socketReady : false,
            uploading : false,
            pushing : false,
            asset : {},
            text : '',
            hasError : false
        },
        
        created : function(){
            var
                socket = new WebSocket('ws://127.0.0.1:8082'),
                // socket = new WebSocket('ws://119.23.56.237:8082'),
                vm = this;

            this.$socket = socket;

            setTimeout(function(){
                if(!vm.socketReady)
                {
                    vm.$Message.error('Socket初始化失败');
                }
            }, 5000);

            socket.onopen = function(){
                vm.socketReady = true;
                vm.$Message.info('Socket初始化成功');
            }

            socket.onmessage = function(event){

            }
        },

        methods : {
            uploadAssetSuccess : function(response){
                if(response.status === 'success'){
                    this.$Message.success('资源上传成功!');
                    delete response.status;
                    this.asset = response;
                }else{
                    this.uploadAssetFail();
                }
            },

            uploadAssetFail : function(){
                this.hasError = true;
                this.$Message.error({
                    content : '资源上传失败',
                    duration : 0
                });  
            },

            beforeAssetUpload : function(){
                this.uploading = true;
            },

            removeAsset : function(){
                this.asset = {};
            },

            post : function(){
                var
                    vm = this;

                if(this.text){
                    axios.get('./uploadText.php?content=' + this.text).then(function(response){
                        if(response.data.status === 'success')
                        {
                            delete response.data.status;
                            vm.push(response.data);
                        }else{
                            return Promise.reject();
                        }
                    }).catch(function(){

                        vm.hasError = true;

                        vm.$Message.error({
                            content : '资源上传失败',
                            duration : 0
                        });
                    });
                }else{
                    this.push();
                }
            },

            push : function(textAsset){
                var
                    assets = [];
                
                if(textAsset){
                    assets.push(textAsset);
                }

                if(this.asset){
                    assets.push(this.asset);
                }

                this.pushing = true;
                this.$socket.send(JSON.stringify(assets));
            }
        }
    })