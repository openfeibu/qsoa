<script type="text/html" id="barDemo">
    @{{#  if(d.pay_status == 'request_pay'){ }}
    <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/pay') }}/@{{ d.id }}">{{ trans('app.pay') }}</a>
    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
</script>