<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airline_bill') }}"><cite>{{ trans('airline_bill.title') }}</cite></a>
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
                <div class="layui-inline">
                    <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
                </div>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>

@include('airline_bill/handle')

<script>
    var main_url = "{{guard_url('airline_bill')}}";
    var delete_all_url = "{{guard_url('airline_bill/destroyAll')}}";
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
                ,{field:'id',title:'ID', width:80, sort: true}
                //,{field:'remaining_day',title:'{{ trans('app.remaining_day') }}'}
                ,{field:'remaining_day_span',title:'{{ trans('app.remaining_day') }}',sort:true}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}',sort:true}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}',sort:true}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}',sort:true}
                ,{field:'sn',title:'{{ trans('airline_bill.label.sn') }}', width:180}
                ,{field:'issuing_date',title:'{{ trans('airline_bill.label.issuing_date') }}', width:180,sort:true}
                ,{field:'agreement_no',title:'{{ trans('airline_bill.label.agreement_no') }}', width:180}
                ,{field:'usg',title:'{{ trans('airline_bill.label.usg') }}', templet:function(d){ return $.formatMoney(d.usg)},sort:true}
                ,{field:'price',title:'{{ trans('airline_bill.label.price') }}', templet:function(d){ return $.formatMoney(d.price,4)} ,sort:true}
                ,{field:'total',title:'{{ trans('airline_bill.label.total') }}', templet:function(d){ return $.formatMoney(d.total)},sort:true}
                ,{field:'tax',title:'{{ trans('airline_bill.label.tax') }}', templet:function(d){ return $.formatMoney(d.tax)},sort:true}
                ,{field:'incl_tax',title:'{{ trans('airline_bill.label.incl_tax') }}', templet:function(d){ return $.formatMoney(d.incl_tax)},sort:true}
                ,{field:'pay_date',title:'{{ trans('airline_bill.label.pay_date') }}',sort:true}
                ,{field:'paid_date',title:'{{ trans('airline_bill.label.paid_date') }}',sort:true}
                ,{field:'paid_total',title:'{{ trans('airline_bill.label.paid_total') }}', templet:function(d){ return $.formatMoney(d.paid_total)},sort:true}
                ,{field:'remark',title:'{{ trans('airline_bill.label.remark') }}',fixed: 'right',width:120}
                ,{field:'status_button',title:'{{ trans('app.status') }}', width:100,fixed: 'right'}
                ,{field:'pay_status_button',title:'{{ trans('app.pay_status') }}',width:100, fixed: 'right'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:380, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
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

{!! Theme::partial('airline_bill_handle_js') !!}