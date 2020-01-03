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
                    <input class="layui-input search_key" name="invoice_date" id="invoice_date" placeholder="<?php echo e(trans('supplier_bill.label.invoice_date')); ?>" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="pay_date" id="pay_date" placeholder="<?php echo e(trans('supplier_bill.label.pay_date')); ?>" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
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
        {{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.edit')); ?></a>
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="<?php echo e(guard_url('supplier_bill/pay')); ?>/{{ d.id }}"><?php echo e(trans('app.pay')); ?></a>
        {{#  } else{ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
        {{#  } }}
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
    {{#  } else if(d.status == 'passed'){ }}
        {{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="<?php echo e(guard_url('supplier_bill/pay')); ?>/{{ d.id }}"><?php echo e(trans('app.pay')); ?></a>
        {{#  } }}
        <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
    {{#  } else if(d.status == 'invalid'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
    {{#  } else{ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
    {{#  } }}
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
                ,{field:'sn',title:'<?php echo e(trans('supplier_bill.label.sn')); ?>', width:180}
                ,{field:'invoice_date',title:'<?php echo e(trans('supplier_bill.label.invoice_date')); ?>',width:140}
                ,{field:'pay_date',title:'<?php echo e(trans('supplier_bill.label.pay_date')); ?>',width:140}
                ,{field:'supplier_name',title:'<?php echo e(trans('supplier.name')); ?>',width:160}
                ,{field:'airport_name',title:'<?php echo e(trans('airport.name')); ?>',width:160}
                ,{field:'airline_name',title:'<?php echo e(trans('airline.name')); ?>',width:160}
                ,{field:'pay_date',title:'<?php echo e(trans('supplier_bill.label.pay_date')); ?>',width:160}
                ,{field:'total',title:'<?php echo e(trans('supplier_bill.label.total')); ?>',width:160}
                ,{field:'paid_date',title:'<?php echo e(trans('supplier_bill.label.paid_date')); ?>',width:160}
                ,{field:'paid_total',title:'<?php echo e(trans('supplier_bill.label.paid_total')); ?>',width:160}
                ,{field:'status_button',title:'<?php echo e(trans('app.status')); ?>',width:100}
                ,{field:'pay_status_button',title:'<?php echo e(trans('app.pay_status')); ?>',width:100}
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
            elem: '#invoice_date'
            ,type: 'date'
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
        });
    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>

