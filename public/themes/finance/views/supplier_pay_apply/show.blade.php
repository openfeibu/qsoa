<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier_pay_apply') }}"><cite>{{ trans('supplier_pay_apply.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier_bill/supplier_pay_apply/'.$supplier_bill->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.apartment') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->apartment }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->airline_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->airport_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->supplier_name }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.supplier_username') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->supplier_username }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.date') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->date }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.reason') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->reason }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.invoice_date') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->invoice_date }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.check_date') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->check_date }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.pay_date') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->pay_date }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.total') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->total }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.payment') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->payment }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.account') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_pay_apply->account }}</p>
                        </div>
                    </div>

                    @if($supplier_bill->pay_status == 'request_pay')

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <a class="layui-btn layui-btn-normal" href="{{ guard_url('supplier_bill/pay/'.$supplier_pay_apply->supplier_bill_id)}}">{{ trans('app.pay') }}</a>
                            <button class="layui-btn layui-btn-warm" lay-submit lay-filter="reject">{{ trans('app.reject') }}</button>
                        </div>
                    </div>
                    @endif
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    layui.use(['jquery','element','table','laydate'], function(){
        var laydate = layui.laydate;
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        laydate.render({
            elem: '#date'
            ,type: 'date'
            ,value:"{{ $supplier_pay_apply->date }}"
        });
        laydate.render({
            elem: '#check_date'
            ,type: 'date'
            ,value:"{{ $supplier_pay_apply->check_date }}"
        });
        form.on('submit(reject)', function(data){
            layer.confirm('{{ trans('messages.confirm_reject') }}', function(index){
                layer.close(index);
                var load = layer.load();
                $.ajax({
                    url : main_url+'/pass',
                    data : {'id':data.id,'_token':"{!! csrf_token() !!}"},
                    type : 'post',
                    success : function (data) {
                        layer.close(load);
                        if(data.code == 0)
                        {
                            table.reload('fb-table', {
                                page: {
                                    curr: nPage //重新从第 1 页开始
                                }
                            });
                        }else{
                            layer.msg(data.message);
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        $.ajax_error(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        });
    });
</script>
