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
                            <input type="text" name="apartment" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="业务部">
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
                            <input type="text" name="supplier_username" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ Auth::user()->name }}">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="date" id="date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.reason') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="reason" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="付供油款{{ $supplier_bill->airline_name }}（{{ $supplier_bill->supply_start_date }} ~ {{ $supplier_bill->supply_end_date }}）">
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
                            <input type="text" name="check_date" id="check_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
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
                            <p class="input-p">{{ number_format($supplier_bill->total,3) }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.payment') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="payment" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="电汇">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_pay_apply.label.account') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="account" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
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
            ,value:"{!! date('Y-m-d') !!}"
        });
        laydate.render({
            elem: '#check_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d') !!}"
        });

    });
</script>
