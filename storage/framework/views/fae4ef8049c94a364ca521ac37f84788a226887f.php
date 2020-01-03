<script type="text/html" id="barDemo">
    {{#  if(d.status == 'new'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass"><?php echo e(trans('app.pass')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } else if(d.status == 'passed'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="add_airline_bill"><?php echo e(trans('airline_bill.add')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } else if(d.status == 'rejected'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } else if(d.status == 'modified'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass"><?php echo e(trans('app.pass')); ?></a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid"><?php echo e(trans('app.invalid')); ?></a>
    {{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.details')); ?></a>
</script>