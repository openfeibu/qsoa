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

                <div class="layui-inline">
                    <input class="layui-input search_key" name="issuing_date" id="issuing_date" placeholder="{{ trans('airline_bill.label.issuing_date') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="sn" id="demoReload" placeholder="{{ trans('airline_bill.label.sn') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="agreement_no" id="demoReload" placeholder="{{ trans('airline_bill.label.agreement_no') }}" autocomplete="off">
                </div>

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
            ,url: "{{guard_url('bill/airline_bill')}}"
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID',totalRowText: 'Total???', width:80, sort: true}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}', width:180}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}', width:180}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}', width:180}
                ,{field:'sn',title:'{{ trans('airline_bill.label.sn') }}', width:180}
                ,{field:'issuing_date',title:'{{ trans('airline_bill.label.issuing_date') }}', width:180}
                ,{field:'agreement_no',title:'{{ trans('airline_bill.label.agreement_no') }}', width:180}
                ,{field:'pay_date',title:'{{ trans('airline_bill.label.pay_date') }}', width:180}
                ,{field:'airline_bill_total',title:'{{ trans('airline_bill.label.total') }}',totalRow: true,toFixed:3, width:180, templet:function(d){ return $.formatMoney(d.airline_bill_total)}}
                ,{field:'paid_date',title:'{{ trans('airline_bill.label.paid_date') }}', width:180}
                ,{field:'airline_bill_paid_total',title:'{{ trans('airline_bill.label.paid_total') }}',totalRow: true,toFixed:3, width:180, templet:function(d){ return $.formatMoney(d.airline_bill_paid_total)}}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:320, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,totalRow: true //???????????????
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
