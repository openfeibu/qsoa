<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm layui-btn-normal" href="{{ guard_url('supplier_bill') }}/@{{ d.supplier_bill_id }}" target="_blank">{{ trans('supplier_bill.name') }}</a>
    @{{#  if(d.status == 'new'){ }}

    @{{#  } else if(d.status == 'checking'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass">{{ trans('app.pass') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reject">{{ trans('app.reject') }}</a>
    <!--<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>-->

    @{{#  } else if(d.status == 'passed'){ }}
    <!--<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>-->
    @{{#  } else if(d.status == 'modified'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass">{{ trans('app.pass') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reject">{{ trans('app.reject') }}</a>
    <!--<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>-->

    @{{#  } else if(d.status == 'rejected'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="pass">{{ trans('app.pass') }}</a>
    <!--<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>-->

    @{{#  } else if(d.status == 'invalid'){ }}

    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
</script>