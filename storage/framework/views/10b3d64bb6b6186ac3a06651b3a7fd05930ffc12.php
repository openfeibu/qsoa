<script>
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "<?php echo csrf_token(); ?>";
            var nPage = $(".layui-laypage-curr em").eq(1).text();
            if(obj.event === 'invalid'){
                layer.confirm('<?php echo e(trans('messages.confirm_invalid')); ?>',{
                    btn: ['该账单','及供应商账单','取消'] //按钮
                }, function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/invalid',
                        data : {'id':data.id,'_token':"<?php echo csrf_token(); ?>"},
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
                }, function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/invalid',
                        data : {'id':data.id,'type':'all','_token':"<?php echo csrf_token(); ?>"},
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
        active.add_airline_bill = function () {
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = 'airline_bill/create';
            var paramStr = "";
            if(data.length == 0)
            {
                layer.msg('请选择数据', {
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                })
                return false;
            }
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?supplier_bill_ids[]="+v.id;
                }else{
                    paramStr += "&supplier_bill_ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            window.location.href=url+paramStr;
        }
    });
</script>