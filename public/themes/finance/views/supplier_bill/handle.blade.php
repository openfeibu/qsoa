<script type="text/html" id="barDemo">
    @{{#  if(d.pay_status == 'unpaid'){ }}

    @{{#  } else if(d.pay_status == 'request_pay'){ }}
    <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">查看并审核付款</a>
    @{{# } else{  }}
        @{{#  if(d.supplier_pay_apply_id){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">查看支付申请</a>
        @{{#  } }}
    @{{#  } }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
</script>