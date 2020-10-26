<script>
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            var nPage = $(".layui-laypage-curr em").eq(1).text();

            if(obj.event === 'pass'){
                layer.confirm('{{ trans('messages.confirm_pass') }}', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/pass',
                        data : {'id':data.id,'_token':"{!! csrf_token() !!}"},
                        type : 'post',
                        success : function (data) {
                            layer.close(load);
                            if(data.code == 0)
                            {
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }else{
                                layer.msg(data.message);
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            }
            else if(obj.event === 'reject'){
                layer.confirm('{{ trans('messages.confirm_reject') }}', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/reject',
                        data : {'id':data.id,'_token':"{!! csrf_token() !!}"},
                        type : 'post',
                        success : function (data) {
                            layer.close(load);
                            if(data.code == 0)
                            {
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }else{
                                layer.msg(data.message);
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            }
            else if(obj.event === 'invalid'){
                layer.confirm('{{ trans('messages.confirm_invalid') }}', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/invalid',
                        data : {'id':data.id,'_token':"{!! csrf_token() !!}"},
                        type : 'post',
                        success : function (data) {
                            layer.close(load);
                            if(data.code == 0)
                            {
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }else{
                                layer.msg(data.message);
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            }

        }
    });
</script>