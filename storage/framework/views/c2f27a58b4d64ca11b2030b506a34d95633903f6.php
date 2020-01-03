<script type="text/html" id="barDemo">
    {{#  if(d.status == 'new'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit"><?php echo e(trans('app.edit')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-warm" href="<?php echo e(guard_url('airline_bill/pay')); ?>/{{ d.id }}"><?php echo e(trans('airline_bill.status.actions.finish')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } else if(d.status == 'invalid'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
    {{#  } else if(d.status == 'finished'){ }}
    <!--<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>-->
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
    {{#  } }}
</script>