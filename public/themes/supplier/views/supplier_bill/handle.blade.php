<script type="text/html" id="barDemo">
    @{{#  if(d.status == 'new'){ }}
        @{{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
        <!--<a class="layui-btn layui-btn-warm layui-btn-sm" lay-event="request_pay">{{ trans('app.request_pay') }}</a>-->
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.request_pay') }}</a>
        @{{#  } else if(d.pay_status == 'request_pay' || d.pay_status == 'rejected'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.edit_request_pay') }}</a>
        @{{#  } else{ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'passed'){ }}

        @{{#  if(d.pay_status == 'unpaid'){ }}
        <!--<a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/pay') }}/@{{ d.id }}">{{ trans('app.pay') }}</a>-->
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.request_pay') }}</a>
        @{{#  } else if(d.pay_status == 'request_pay' || d.pay_status == 'rejected'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.edit_request_pay') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else if(d.status == 'rejected'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'modified'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
        @{{#  if(d.pay_status == 'unpaid' || d.pay_status == 'refund'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.request_pay') }}</a>
        @{{#  } else if(d.pay_status == 'request_pay'|| d.pay_status == 'rejected'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.edit_request_pay') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'invalid'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'bill'){ }}
        @{{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.request_pay') }}</a>
        @{{#  } else if(d.pay_status == 'request_pay' || d.pay_status == 'rejected'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.edit_request_pay') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else if(d.status == 'finished'){ }}
        @{{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.request_pay') }}</a>
        @{{#  } else if(d.pay_status == 'request_pay' || d.pay_status == 'rejected'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/supplier_pay_apply') }}/@{{ d.id }}">{{ trans('app.edit_request_pay') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else{ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } }}
</script>