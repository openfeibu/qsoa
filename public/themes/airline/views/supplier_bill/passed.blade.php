<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier_bill') }}"><cite>{{ trans('supplier_bill.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                {!! Theme::widget('BillSearch')->render() !!}
                <div class="layui-inline">
                    <input class="layui-input search_key" name="invoice_date" id="invoice_date" placeholder="{{ trans('supplier_bill.label.invoice_date') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="pay_date" id="pay_date" placeholder="{{ trans('supplier_bill.label.pay_date') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="sn" id="demoReload" placeholder="{{ trans('supplier_bill.label.sn') }}" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>

@include('supplier_bill/handle')

<script>
    var main_url = "{{guard_url('supplier_bill')}}";
    var index_url = "{{guard_url('passed_supplier_bill')}}";
    var delete_all_url = "{{guard_url('supplier_bill/destroyAll')}}";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: index_url
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'sn',title:'{{ trans('supplier_bill.label.sn') }}', width:180}
                ,{field:'invoice_date',title:'{{ trans('supplier_bill.label.invoice_date') }}'}
                ,{field:'pay_date',title:'{{ trans('supplier_bill.label.pay_date') }}'}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}'}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}'}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}'}
                ,{field:'mt',title:'{{ trans('supplier_bill.label.mt') }}'}
                ,{field:'usg',title:'{{ trans('supplier_bill.label.usg') }}'}
                ,{field:'price',title:'{{ trans('supplier_bill.label.price') }}'}
                ,{field:'total',title:'{{ trans('supplier_bill.label.total') }}'}
                ,{field:'status_button',title:'{{ trans('app.status') }}'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:280, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
            elem: '#invoice_date'
            ,type: 'date'
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}

{!! Theme::partial('supplier_bill_handle_js') !!}