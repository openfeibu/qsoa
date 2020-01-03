<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('supplier_bill_item')); ?>"><cite><?php echo e(trans('supplier_bill_item.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="<?php echo e(guard_url('supplier_bill_item/create')); ?>"><?php echo e(trans('app.add')); ?></a></button>
                </div>
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-primary " data-type="add_supplier_bill" data-events="add_supplier_bill"><?php echo e(trans('supplier_bill.add')); ?></button>
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
                    <input class="layui-input search_key" name="flight_date" id="flight_date" placeholder="<?php echo e(trans('supplier_bill_item.label.flight_date')); ?>" autocomplete="off">
                </div>

                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.edit')); ?></a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
    <a class="layui-btn layui-btn-sm" href="<?php echo e(guard_url('supplier_bill_item/create?id=')); ?>{{ d.id }}"><?php echo e(trans('app.copy')); ?></a>
</script>

<script>
    var main_url = "<?php echo e(guard_url('supplier_bill_item')); ?>";
    var delete_all_url = "<?php echo e(guard_url('supplier_bill_item/destroyAll')); ?>";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '<?php echo e(guard_url('supplier_bill_item')); ?>'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'flight_date',title:'<?php echo e(trans('supplier_bill_item.label.flight_date')); ?>',width:160}
                ,{field:'supplier_name',title:'<?php echo e(trans('supplier.name')); ?>'}
                ,{field:'airport_name',title:'<?php echo e(trans('airport.name')); ?>'}
                ,{field:'airline_name',title:'<?php echo e(trans('airline.name')); ?>'}
                ,{field:'total',title:'<?php echo e(trans('supplier_bill_item.label.total')); ?>'}
                ,{field:'score',title:'<?php echo e(trans('app.actions')); ?>', width:220, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
            elem: '#flight_date'
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

        active.add_supplier_bill = function(){
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = 'supplier_bill/create';
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
                    paramStr += "?supplier_bill_item_ids[]="+v.id;
                }else{
                    paramStr += "&supplier_bill_item_ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            window.location.href=url+paramStr;
        }
    });
</script>