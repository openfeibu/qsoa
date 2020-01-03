<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('supplier_bill')); ?>"><cite><?php echo e(trans('supplier_bill.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.add')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('supplier_bill')); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airport.name')); ?></label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="airport_id" value="<?php echo e($airport->id); ?>">
                            <p class="input-p"><?php echo e($airport->name); ?></p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airline.name')); ?></label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="airline_id" value="<?php echo e($airline->id); ?>">
                            <p class="input-p"><?php echo e($airline->name); ?></p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('supplier_bill.label.invoice_date')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="invoice_date" id="invoice_date"  lay-verify="required" autocomplete="off" placeholder="" class="layui-input invoice_date" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('supplier_bill.label.pay_date')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="pay_date" id="pay_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <table lay-filter="supplier_bill_item" id="supplier_bill_item">
                            <thead>
                            <tr>
                                <th lay-data="{field:'id',hide:true}">ID</th>
                                <th lay-data="{field:'flight_date'}"><?php echo e(trans('supplier_bill_item.label.flight_date')); ?></th>
                                <th lay-data="{field:'airport_name'}"><?php echo e(trans('airport.name')); ?></th>
                                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th lay-data="{field:'<?php echo e($field['id']); ?>'}"><?php echo e($field['field']); ?><?php echo e($field['field_comment'] ? ($field['field_comment']) : ''); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th lay-data="{field:'total'}"><?php echo e(trans('supplier_bill_item.label.total')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $supplier_bill_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $supplier_bill_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($supplier_bill_item['id']); ?></td>
                                    <td><?php echo e($supplier_bill_item['flight_date']); ?></td>
                                    <td><?php echo e($supplier_bill_item['airport_name']); ?></td>
                                    <?php $__currentLoopData = $supplier_bill_item['infos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info_key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e($info['field_value']); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e($supplier_bill_item['total']); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('supplier_bill.label.total')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="total" lay-verify="required|number" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($total); ?>" >
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" type="button" lay-filter="supplier_bill_item_create"><?php echo e(trans('app.submit_now')); ?></button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                </form>
            </div>

        </div>
    </div>
</div>
<?php echo Theme::asset()->container('ueditor')->scripts(); ?>

<script>
    var ue = getUe();
</script>

<script>
    layui.use(['jquery','element','table','laydate'], function(){
        var laydate = layui.laydate;
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.init('supplier_bill_item', {
            cellMinWidth :'180'
        });

        laydate.render({
            elem: '#invoice_date'
            ,type: 'date'
            ,value:"<?php echo date('Y-m-d'); ?>"
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
            ,value:"<?php echo date('Y-m-d'); ?>"
        });

        form.on('submit(supplier_bill_item_create)', function(){
            var data = layui.table.cache.supplier_bill_item;
            var form_data = {};
            var supplier_bill_item_ids = [];
            $.each(data, function(i, val) {
                supplier_bill_item_ids[i] = val.id;
            });
            var invoice_date = $("input[name='invoice_date']").val();
            var pay_date = $("input[name='pay_date']").val();
            var total = $("input[name='total']").val();
            var airport_id = $("input[name='airport_id']").val();
            var airline_id = $("input[name='airline_id']").val();
            var load = layer.load();
            $.ajax({
                url : "<?php echo e(guard_url('supplier_bill')); ?>",
                data : {'supplier_bill_item_ids':supplier_bill_item_ids,'airport_id':airport_id,'airline_id':airline_id,'invoice_date':invoice_date,'pay_date':pay_date,'total':total,'_token':"<?php echo csrf_token(); ?>"},
                type : 'POST',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0)
                    {
                        window.location.href = data.url;
                    }else{
                        layer.msg(data.message);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
            return false;
        });
    });
</script>

