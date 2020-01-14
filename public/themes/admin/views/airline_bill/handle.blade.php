<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm layui-btn-normal" href="{{ guard_url('supplier_bill') }}/@{{ d.supplier_bill_id }}" target="_blank">{{ trans('supplier_bill.name') }}</a>
    @{{#  if(d.status == 'new'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">{{ trans('app.details') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-warm" href="{{ guard_url('airline_bill/pay') }}/@{{ d.id }}">{{ trans('airline_bill.status.actions.finish') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>
    @{{#  } else if(d.status == 'invalid'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else if(d.status == 'finished'){ }}
    <a class="layui-btn layui-btn-sm layui-btn-normal"  href="{{ guard_url('airline_bill/download_word') }}/@{{ d.id }}">{{ trans('app.download') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="invalid">{{ trans('app.invalid') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-normal" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } }}
</script>