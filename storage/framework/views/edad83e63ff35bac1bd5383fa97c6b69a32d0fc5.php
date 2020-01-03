<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(route('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('contract.index')); ?>"><cite><?php echo e(trans('contract.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.edit')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('contract/'.$contract->id)); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.contract_partner')); ?></label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="airline_name"><?php echo e($contractable->name); ?></p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label"><?php echo e(trans('airport.name')); ?></label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="airline_name"><?php echo e($airport->name); ?></p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.name')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($contract->name); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.date')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="date" id="date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($contract->start_time); ?> ~ <?php echo e($contract->end_time); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.increase_price')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="increase_price"  lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="<?php echo e($contract->increase_price); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('contract.label.image')); ?></label>
                        <?php echo $contract->files('images')
                        ->url($contract->getUploadUrl('images'))
                        ->deleteUrl(guard_url('contract/destroy_image'))
                        ->uploaders(); ?>

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
            ,value: '<?php echo e($contract->start_time); ?> ~ <?php echo e($contract->end_time); ?>'
            ,range: '~' //或 range: '~' 来自定义分割字符
        });
        form.on('radio(airports)', function(data){
            $("#name").val($("#airline_name").text()+' - '+data.elem.title);
        });
    });
</script>