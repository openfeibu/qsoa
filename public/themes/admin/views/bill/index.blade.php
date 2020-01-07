<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('bill') }}"><cite>{{ trans('bill.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value="">{{ trans('airport.name') }}</option>
                        @foreach($airports as $key => $airport)
                            <option value="{{ $airport['id'] }}">{{ $airport['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>


<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('bill') }}/@{{ d.id }}">{{ trans('app.details') }}</a>
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
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}', width:180}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}', width:180}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}', width:180}
                ,{field:'pay_date',title:'{{ trans('airline_bill.label.pay_date') }}', width:180}
                ,{field:'final_total',title:'{{ trans('airline_bill.label.final_total') }}', width:180}
                ,{field:'paid_date',title:'{{ trans('airline_bill.label.paid_date') }}', width:180}
                ,{field:'paid_total',title:'{{ trans('airline_bill.label.paid_total') }}', width:180}
                ,{title:'{{ trans('supplier_bill.label.pay_date') }}', width:180,templet:'<div>@{{ d.supplier_bill.pay_date }}</div>'}
                ,{title:'{{ trans('supplier_bill.label.total') }}', width:180,templet:'<div>@{{ d.supplier_bill.total }}</div>'}
                ,{title:'{{ trans('supplier_bill.label.paid_date') }}', width:180,templet:'<div>@{{ d.supplier_bill.paid_date }}</div>'}
                ,{title:'{{ trans('supplier_bill.label.paid_total') }}', width:180,templet:'<div>@{{ d.supplier_bill.paid_total }}</div>'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:260, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
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
