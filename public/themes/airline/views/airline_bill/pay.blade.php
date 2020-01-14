<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airline_bill') }}"><cite>{{ trans('airline_bill.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.pay') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('airline_bill/pay/'.$airline_bill->id)}}" method="post" lay-filter="fb-form">

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.paid_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="paid_date" id="paid_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.paid_total') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="paid_total" lay-verify="required|number" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline_bill->total }}" >
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" >{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>
{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>
    var ue = getUe();
</script>

<script>
    layui.use(['jquery','element','table','laydate'], function(){
        var laydate = layui.laydate;
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        laydate.render({
            elem: '#paid_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d') !!}"
        });
    });
</script>

