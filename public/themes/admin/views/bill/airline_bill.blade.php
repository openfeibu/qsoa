<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('bill') }}"><cite>{{ trans('airline_bill.name') }} {{ trans('bill.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                {!! Theme::widget('BillSearch')->render() !!}

                <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>


<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm layui-btn-normal" href="{{ guard_url('supplier_bill') }}/@{{ d.supplier_bill_id }}" target="_blank">{{ trans('supplier_bill.name') }}</a>
    <a class="layui-btn layui-btn-sm layui-btn-normal" href="{{ guard_url('airline_bill') }}/@{{ d.id }}" target="_blank">{{ trans('airline_bill.name') }}</a>
</script>
<script>
    var main_url = "{{guard_url('bill')}}";
    var delete_all_url = "{{guard_url('bill/destroyAll')}}";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: main_url
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID',totalRowText: 'Total：', width:80, sort: true}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}', width:180}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}', width:180}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}', width:180}
                ,{field:'pay_date',title:'{{ trans('airline_bill.label.pay_date') }}', width:180}
                ,{field:'airline_bill_total',title:'{{ trans('airline_bill.label.total') }}',totalRow: true,toFixed:3, width:180}
                ,{field:'paid_date',title:'{{ trans('airline_bill.label.paid_date') }}', width:180}
                ,{field:'airline_bill_paid_total',title:'{{ trans('airline_bill.label.paid_total') }}',totalRow: true,toFixed:3, width:180}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:320, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,totalRow: true //开启合计行
            ,done:function () {
                element.init();
            }
        });
        laydate.render({
            elem: '#issuing_date'
            ,type: 'date'
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}
