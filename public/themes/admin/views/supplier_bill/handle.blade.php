<!--
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
-->

<script type="text/html" id="barDemo">
    @{{#  if(d.status == 'new'){ }}
    @{{#  if(d.pay_status == 'unpaid'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>

    @{{#  } else{ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } }}
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'passed'){ }}
    @{{#  if(d.pay_status == 'unpaid'){ }}

    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else if(d.status == 'rejected'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'modified'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    @{{#  if(d.pay_status == 'unpaid' || d.pay_status == 'refund'){ }}

    @{{#  } }}
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'invalid'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'bill'){ }}
    @{{#  if(d.pay_status == 'unpaid'){ }}

    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else if(d.status == 'finished'){ }}
    @{{#  if(d.pay_status == 'unpaid'){ }}

    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else{ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } }}
</script>