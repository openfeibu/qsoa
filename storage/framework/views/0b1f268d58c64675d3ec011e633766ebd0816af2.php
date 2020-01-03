<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(route('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('airline.index')); ?>"><cite><?php echo e(trans('airline.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.edit')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('airline/'.$airline->id)); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('airline.label.name')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($airline->name); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('airline.label.leader')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="leader" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($airline->leader); ?>" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('airline.label.tel')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="tel" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($airline->tel); ?>" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('airline.label.email')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" lay-verify="required|email" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($airline->email); ?>" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('airline.label.position')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="position" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($airline->position); ?>">
                        </div>
                    </div>

                    <?php echo Theme::widget('area',['country_id' => $airline->country_id,'province_id' => $airline->province_id,'city_id' => $airline->city_id ])->render(); ?>


                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airline.label.can_cooperative_airport')); ?></label>
                        <div class="layui-input-inline">
                            <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="checkbox" name="airports[]" title="<?php echo e($airport->name); ?>"  value="<?php echo e($airport->id); ?>" <?php if(in_array($airport->id ,$can_cooperative_airport_ids)): ?> checked <?php endif; ?>>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1"><?php echo e(trans('app.submit_now')); ?></button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>
<?php echo Theme::asset()->container('ueditor')->scripts(); ?>

<script>
    var ue = getUe();
</script>

