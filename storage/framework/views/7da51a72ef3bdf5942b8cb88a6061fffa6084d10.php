<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(route('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('contract.index')); ?>"><cite><?php echo e(trans('contract.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.add')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('supplier_contract')); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('supplier.name')); ?></label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="supplier_name"><?php echo e($supplier->name); ?></p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airport.name')); ?></label>
                        <div class="layui-input-inline">
                            <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(in_array($airport->id,$exist_airport_ids)): ?>
                                    <input type="radio" name="airport_id" title="<?php echo e($airport->name); ?> (<?php echo e(trans('app.have_cooperation')); ?>)"  value="<?php echo e($airport->id); ?>" lay-filter="airports" lay-verify="required" disabled >
                                <?php else: ?>
                                    <input type="radio" name="airport_id" title="<?php echo e($airport->name); ?>"  value="<?php echo e($airport->id); ?>" lay-filter="airports" lay-verify="required" >
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.name')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.date')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="date" id="date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.image')); ?></label>
                        <?php echo $contract->files('images')
                        ->url($contract->getUploadUrl('images'))
                        ->uploaders(); ?>

                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <?php if(count($airports) == count($exist_airport_ids)): ?>
                                <button class="layui-btn layui-btn-disabled " lay-submit="" lay-filter="demo1"  disabled ><?php echo e(trans('app.submit_now')); ?></button>
                            <?php else: ?>
                                <button class="layui-btn" lay-submit="" lay-filter="demo1"><?php echo e(trans('app.submit_now')); ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="supplier">
                    <input type="hidden" name="supplier_id" value="<?php echo e($supplier->id); ?>">
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

        //执行一个laydate实例
        laydate.render({
            elem: '#date'
            ,type: 'date'
            ,range: '~' //或 range: '~' 来自定义分割字符
        });
        form.on('radio(airports)', function(data){
            $("#name").val($("#supplier_name").text()+' - '+data.elem.title);
        });
    });
</script>