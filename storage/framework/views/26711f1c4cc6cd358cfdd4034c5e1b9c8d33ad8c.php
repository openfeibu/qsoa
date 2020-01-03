<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans("supplier_user.name")); ?></cite></a><span lay-separator="">/</span>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('supplier_user/'.$supplier_user->id)); ?>" method="post" lay-filter="fb-form">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans("supplier_user.label.email")); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" value="<?php echo e($supplier_user->email); ?>" lay-verify="email|required" autocomplete="off" placeholder="请输入<?php echo e(trans("supplier_user.label.email")); ?>" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans("supplier_user.label.name")); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" value="<?php echo e($supplier_user->name); ?>" lay-verify="required" autocomplete="off" placeholder="请输入<?php echo e(trans("supplier_user.label.name")); ?>" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans("supplier_user.label.new_password")); ?></label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" placeholder="请输入<?php echo e(trans("supplier_user.label.password")); ?>，不改则留空" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux">请输入密码，不改则留空</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans("supplier_user.label.roles")); ?></label>
                        <div class="layui-input-block">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <input type="radio" name="roles[]" value="<?php echo e($role->id); ?>" title="<?php echo e($role->name); ?>" <?php echo e(!($supplier_user->hasRole($role->slug)) ? :'checked'); ?>>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    layui.use('form', function(){
        var form = layui.form;

        form.render();
    });
</script>

