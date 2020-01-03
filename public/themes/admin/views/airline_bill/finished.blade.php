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

@include('airline_bill/handle')

<script>
    var main_url = "{{guard_url('airline_bill')}}";
    var index_url = "{{guard_url('finished_airline_bill')}}";
    var delete_all_url = "{{guard_url('airline_bill/destroyAll')}}";
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
                ,{field:'issuing_date',title:'{{ trans('airline_bill.label.issuing_date') }}', width:180}
                ,{field:'sn',title:'{{ trans('airline_bill.label.sn') }}', width:180}
                ,{field:'agreement_no',title:'{{ trans('airline_bill.label.agreement_no') }}', width:180}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}', width:180}
                ,{field:'total',title:'{{ trans('airline_bill.label.total') }}', width:180}
                ,{field:'final_total',title:'{{ trans('airline_bill.label.final_total') }}', width:180}
                ,{field:'paid_date',title:'{{ trans('airline_bill.label.paid_date') }}', width:180}
                ,{field:'paid_total',title:'{{ trans('airline_bill.label.paid_total') }}', width:180}
                ,{field:'status_button',title:'{{ trans('app.status') }}', width:180}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:160, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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

{!! Theme::partial('airline_bill_handle_js') !!}