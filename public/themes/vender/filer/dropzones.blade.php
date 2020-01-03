<div class="layui-input-block">
    <div class="layui-upload">
        <button type="button" class="layui-btn" id="uploadImage_{!!$field!!}">多图片上传</button>
        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;width: 88%">
            预览图：
            <div class="layui-upload-list uploader-list" style="overflow: auto;" id="uploader-list-{!!$field!!}">
                @foreach($files as $file)
                    <div class="file-iteme">
                        <div class="handle">
                            <i class="layui-icon layui-icon-left left" style="font-size: 30px; color: #fff;"></i>
                            <i class="layui-icon layui-icon-right right" style="font-size: 30px; color: #fff;"></i>
                            <i class="layui-icon layui-icon-delete delete" style="font-size: 30px; color: #fff;"></i>
                        </div>
                        <img src="{!!url("/image/original".$file['path'])!!}">
                        <input type="hidden" name="{!!$field!!}[]" id="path_{!!$field!!}" class="path" value="{{$file['path']}}"/>
                     </div>
                @endforeach
            </div>
        </blockquote>
    </div>
</div>

<script>
    layui.use(['jquery','element','form','table','upload'], function(){
        var $ = layui.$;
        var form = layui.form;
        var upload = layui.upload;
        upload.render({
            elem: '#uploadImage_{!!$field!!}'
            ,accept:'images'
            ,url: '{!! $url !!}'
            ,multiple:true
            ,data: {
                '_token':$('meta[name="csrf-token"]').attr('content')
            }
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res, index, upload){
                var imgMsg = {
                    url: res.data.url,
                }
                //<i class="layui-icon layui-icon-left left" style="font-size: 30px; color: #fff;"></i><i class="layui-icon layui-icon-right right" style="font-size: 30px; color: #fff;"></i>
                $('#uploader-list-{!!$field!!}').append(
                    '<div class="file-iteme">' +
                    '<div class="handle"> <i class="layui-icon layui-icon-left left" style="font-size: 30px; color: #fff;"></i><i class="layui-icon layui-icon-right right" style="font-size: 30px; color: #fff;"></i><i class="layui-icon layui-icon-delete delete" style="font-size: 30px; color: #fff;"></i>  </div>' +
                    '<img src='+ res.data.url +'>' +
                    '<input type="hidden" name="{!!$field!!}[]" id="path_{!!$field!!}" value="'+ res.data.path +'"/>' +
                    '</div>'
                );
                layer.closeAll('loading'); //关闭loading
                layer.msg(res.message);

            }
            ,error: function(index, upload){
                layer.closeAll('loading'); //关闭loading
            }
        });

        $(document).on("mouseenter mouseleave", ".file-iteme", function(event){
            if(event.type === "mouseenter"){
                $(this).children(".info").fadeIn("fast");
                $(this).children(".handle").fadeIn("fast");
            }else if(event.type === "mouseleave") {
                $(this).children(".info").hide();
                $(this).children(".handle").hide();
            }
        });

        $(document).on("click", ".file-iteme .left", function(event){
            var index = $(this).parents(".file-iteme").index();
            var html = '<div class="file-iteme">' + $(this).parents(".file-iteme").html() + '</div>';
            if(index > 0)
            {
                $(this).parents(".uploader-list").find(".file-iteme").eq(index-1).before(html);
                $(this).parents(".file-iteme").remove();
            }
        });
        $(document).on("click", ".file-iteme .right", function(event){
            var index_count = $(this).parents(".uploader-list").find(".file-iteme").length - 1 ;
            var index = $(this).parents(".file-iteme").index();
            var html = '<div class="file-iteme">' + $(this).parents(".file-iteme").html() + '</div>';
            if(index != index_count)
            {
                $(this).parents(".uploader-list").find(".file-iteme").eq(index+1).after(html);
                $(this).parents(".file-iteme").remove();
            }
        });
        $(document).on("click", ".file-iteme .delete", function(event){
            var that = $(this);
            var path = that.parents(".file-iteme").find('.path').val();
            layer.confirm('真的删除行么', function(index){
                layer.close(index);
                var load = layer.load();
                $.ajax({
                    url : "{{ $delete_url }}" ,
                    data : {'url':path,'_token' : "{!! csrf_token() !!}"},
                    type : 'post',
                    success : function (data) {
                        that.parents(".file-iteme").remove();
                        layer.close(load);
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        layer.msg('服务器出错');
                    }
                });
            });

        });

    });
</script>