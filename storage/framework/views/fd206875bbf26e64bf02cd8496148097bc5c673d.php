<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('supplier_bill_item')); ?>"><cite><?php echo e(trans('supplier_bill_item.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.edit')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('supplier_bill_item/'.$supplier_bill_item['id'])); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airport.name')); ?></label>
                        <div class="layui-input-inline">
                            <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" name="airport_id" title="<?php echo e($airport->name); ?>"  value="<?php echo e($airport->id); ?>" lay-filter="airports" lay-verify="otherReq" <?php if($airport['id'] == $supplier_bill_item['airport_id']): ?> checked <?php endif; ?> disabled>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airline.name')); ?></label>
                        <div class="layui-input-inline">
                            <?php $__currentLoopData = $airlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" name="airline_id" title="<?php echo e($airline->name); ?>"  value="<?php echo e($airline->id); ?>" lay-filter="airlines" lay-verify="otherReq"  <?php if($airline['id'] == $supplier_bill_item['airline_id']): ?> checked <?php endif; ?> disabled>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('supplier_bill_item.label.flight_date')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="flight_date" id="flight_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input flight_date" value="<?php echo e($supplier_bill_item['flight_date']); ?>">
                        </div>
                    </div>
                    <?php $__currentLoopData = $supplier_bill_item_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="layui-form-item">
                            <label class="layui-form-label"><?php echo e($field['field']); ?><?php echo e($field['field_comment'] ? ($field['field_comment']) : ''); ?></label>
                            <div class="layui-input-inline">
                                <input type="text" name="field[<?php echo e($field['id']); ?>]" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($field['field_value']); ?>">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('supplier_bill_item.label.total')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="total" lay-verify="required|number" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($supplier_bill_item['total']); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1"><?php echo e(trans('app.submit_now')); ?></button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="previous_url" value="<?php echo e($previous_url); ?>">
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

        //????????????laydate??????
        laydate.render({
            elem: '#invoice_date'
            ,type: 'date'
        });
        laydate.render({
            elem: '#flight_date'
            ,type: 'date'
        });

    });
</script>