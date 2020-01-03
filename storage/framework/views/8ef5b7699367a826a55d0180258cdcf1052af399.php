<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('bill')); ?>"><cite><?php echo e(trans('bill.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value=""><?php echo e(trans('airport.name')); ?></option>
                        <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($airport['id']); ?>"><?php echo e($airport['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>



<script>
    var main_url = "<?php echo e(guard_url('bill')); ?>";
    var delete_all_url = "<?php echo e(guard_url('bill/destroyAll')); ?>";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: main_url
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'airport_name',title:'<?php echo e(trans('airport.name')); ?>', width:180}
                ,{field:'supplier_name',title:'<?php echo e(trans('supplier.name')); ?>', width:180}
                ,{field:'airline_name',title:'<?php echo e(trans('airline.name')); ?>', width:180}
                ,{field:'pay_date',title:'<?php echo e(trans('airline_bill.label.pay_date')); ?>', width:180}
                ,{field:'final_total',title:'<?php echo e(trans('airline_bill.label.final_total')); ?>', width:180}
                ,{field:'paid_date',title:'<?php echo e(trans('airline_bill.label.paid_date')); ?>', width:180}
                ,{field:'paid_total',title:'<?php echo e(trans('airline_bill.label.paid_total')); ?>', width:180}
                ,{title:'<?php echo e(trans('supplier_bill.label.pay_date')); ?>', width:180,templet:'<div>{{ d.supplier_bill.pay_date }}</div>'}
                ,{title:'<?php echo e(trans('supplier_bill.label.total')); ?>', width:180,templet:'<div>{{ d.supplier_bill.total }}</div>'}
                ,{title:'<?php echo e(trans('supplier_bill.label.paid_date')); ?>', width:180,templet:'<div>{{ d.supplier_bill.paid_date }}</div>'}
                ,{title:'<?php echo e(trans('supplier_bill.label.paid_total')); ?>', width:180,templet:'<div>{{ d.supplier_bill.paid_total }}</div>'}
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
            elem: '#issuing_date'
            ,type: 'date'
        });
    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>

