<script type="text/html" id="barDemo">
    @{{#  if(d.status == 'new'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass">{{ trans('app.pass') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>
    @{{#  } else if(d.status == 'passed'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>
    @{{#  } else if(d.status == 'rejected'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>
    @{{#  } else if(d.status == 'modified'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass">{{ trans('app.pass') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>
    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
</script>