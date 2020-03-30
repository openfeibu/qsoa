
<div class="layui-input-block">
    <div id="upload_file_content_{!!$field!!}">
        <button type="button" class="layui-btn layui-btn-normal" id="uploadFile_{!!$field!!}">选择文件</button>
        <button type="button" class="layui-btn" id="uploadFile_action_{!!$field!!}">开始上传</button>
        @if($files)
            <a type="button" class="layui-btn" href="{!!url("/image/download".$files['path'])!!}" id="file_{!!$field!!}">{{ trans('app.download') }}</a>
        @else
            <a type="button" class="layui-btn" href="" style="display: none" id="file_{!!$field!!}">{{ trans('app.download') }}</a>
        @endif

    </div>
</div>
<input type="hidden" name="{!!$field!!}" id="path_{!!$field!!}" value="@if($files){{$files['path']}}@endif"/>
<script>
    layui.use(['jquery','element','form','table','upload'], function(){
        var $ = layui.$;
        var form = layui.form;
        var upload = layui.upload;
        upload.render({
            elem: '#uploadFile_{!!$field!!}'
            ,accept:'file'
            ,url: '{!! $url !!}'
            ,auto: false
            ,bindAction: '#uploadFile_action_{!!$field!!}'
            ,data: {
                '_token':$('meta[name="csrf-token"]').attr('content')
            }
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res, index, upload){
                console.log(res)
                layer.msg(res.message);
                layer.closeAll('loading'); //关闭loading
                if(res.code == 0)
                {
                    $("#path_{!!$field!!}").val(res.data.path);
                    $('#file_{!!$field!!}').show().attr('href', res.data.url);
                }
            }
            ,error: function(index, upload){
                layer.closeAll('loading'); //关闭loading
            }
        });
    });
</script>