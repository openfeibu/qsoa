<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('supplier_bill')); ?>"><cite><?php echo e(trans('supplier_bill.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-primary " data-type="add_airline_bill" data-events="add_airline_bill"><?php echo e(trans('airline_bill.add')); ?></button>
                </div>
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value=""><?php echo e(trans('airport.name')); ?></option>
                        <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($airport['id']); ?>"><?php echo e($airport['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value=""><?php echo e(trans('airline.name')); ?></option>
                        <?php $__currentLoopData = $airlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($airline['id']); ?>"><?php echo e($airline['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="date" id="date" placeholder="<?php echo e(trans('supplier_bill.label.date')); ?>" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <select name="status" class="layui-select search_key">
                        <option value=""><?php echo e(trans('app.status')); ?></option>
                        <?php $__currentLoopData = trans('supplier_bill.status.one-level'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $status_desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($status_desc); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="sn" id="demoReload" placeholder="<?php echo e(trans('supplier_bill.label.sn')); ?>" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    {{#  if(d.status == 'new'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass"><?php echo e(trans('app.pass')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reject"><?php echo e(trans('app.reject')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } else if(d.status == 'passed'){ }}

    {{#  } else if(d.status == 'rejected'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } else if(d.status == 'modified'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass"><?php echo e(trans('app.pass')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reject"><?php echo e(trans('app.reject')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
</script>

<script>
    var main_url = "<?php echo e(guard_url('supplier_bill')); ?>";
    var delete_all_url = "<?php echo e(guard_url('supplier_bill/destroyAll')); ?>";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '<?php echo e(guard_url('supplier_bill')); ?>'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'date',title:'<?php echo e(trans('supplier_bill.label.date')); ?>'}
                ,{field:'sn',title:'<?php echo e(trans('supplier_bill.label.sn')); ?>', width:180}
                ,{field:'supplier_name',title:'<?php echo e(trans('supplier.name')); ?>'}
                ,{field:'airport_name',title:'<?php echo e(trans('airport.name')); ?>'}
                ,{field:'airline_name',title:'<?php echo e(trans('airline.name')); ?>'}
                ,{field:'total',title:'<?php echo e(trans('supplier_bill.label.total')); ?>'}
                ,{field:'status_button',title:'<?php echo e(trans('app.status')); ?>'}
                ,{field:'score',title:'<?php echo e(trans('app.actions')); ?>', width:260, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '<?php echo e(config('app.limit')); ?>'
            ,height: 'full-200'
            ,done:function () {
                element.init();
            }
        });
        laydate.render({
            elem: '#date'
            ,type: 'date'
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
            if(obj.event === 'pass'){
                layer.confirm('<?php echo e(trans('messages.confirm_pass')); ?>', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/pass',
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
                });
            }else if(obj.event === 'reject'){
                layer.confirm('<?php echo e(trans('messages.confirm_reject')); ?>', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/reject',
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
                });
            }
        }

    });
</script>

<?php echo Theme::partial('supplier_bill_handle_js'); ?>