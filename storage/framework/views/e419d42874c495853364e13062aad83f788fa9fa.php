<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('airport')); ?>"><cite><?php echo e(trans('airport.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="<?php echo e(guard_url('airport/create')); ?>"><?php echo e(trans('app.add')); ?></a></button>
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del"><?php echo e(trans('app.delete')); ?></button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="search_name" id="demoReload" placeholder="<?php echo e(trans('airport.label.name')); ?>/<?php echo e(trans('airport.label.code')); ?>" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="top_up"><?php echo e(trans('app.top_up')); ?></a>
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.edit')); ?></a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
</script>
<script type="text/html" id="imageTEM">
    <img src="{{d.image}}" alt="" height="28">
</script>

<script>
    var main_url = "<?php echo e(guard_url('airport')); ?>";
    var delete_all_url = "<?php echo e(guard_url('airport/destroyAll')); ?>";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '<?php echo e(guard_url('airport')); ?>'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'<?php echo e(trans('airport.label.name')); ?>',edit:'text'}
                ,{field:'code',title:'<?php echo e(trans('airport.label.code')); ?>',edit:'text'}
                ,{field:'leader',title:'<?php echo e(trans('airport.label.leader')); ?>',edit:'text'}
                ,{field:'area',title:'<?php echo e(trans('app.area')); ?>'}
                ,{field:'used_balance',title:'<?php echo e(trans('airport.label.used_balance')); ?>'}
                ,{field:'balance',title:'<?php echo e(trans('airport.label.balance')); ?>'}
                ,{field:'score',title:'<?php echo e(trans('app.actions')); ?>', width:200, align: 'right',toolbar:'#barDemo'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '<?php echo e(config('app.limit')); ?>'
            ,height: 'full-200'
        });
    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>


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
            if(obj.event === 'top_up'){
                layer.prompt({
                    formType: 0,
                    value: '',
                    title: '<?php echo e(trans('app.top_up')); ?>',
                }, function(value, index, elem){
                    layer.close(index);
                    // ????????????
                    var load = layer.load();
                    $.ajax({
                        url : "<?php echo e(guard_url('airport/top_up')); ?>/"+data.id,
                        data : {'total':value,'_token':"<?php echo csrf_token(); ?>"},
                        type : 'POST',
                        success : function (data) {
                            layer.close(load);
                            var nPage = $(".layui-laypage-curr em").eq(1).text();
                            //????????????
                            table.reload('fb-table', {

                            });
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