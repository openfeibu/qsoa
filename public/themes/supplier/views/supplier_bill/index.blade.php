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
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value="">{{ trans('airport.name') }}</option>
                        @foreach($airports as $key => $airport)
                            <option value="{{ $airport['id'] }}">{{ $airport['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value="">{{ trans('airline.name') }}</option>
                        @foreach($airlines as $key => $airline)
                            <option value="{{ $airline['id'] }}">{{ $airline['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="invoice_date" id="invoice_date" placeholder="{{ trans('supplier_bill.label.invoice_date') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="pay_date" id="pay_date" placeholder="{{ trans('supplier_bill.label.pay_date') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key">
                        <option value="">{{ trans('app.status') }}</option>
                        @foreach(trans('supplier_bill.status.one-level') as $key => $status_desc)
                            <option value="{{ $key }}">{{ $status_desc }}</option>
                        @endforeach
                    </select>
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
<script type="text/html" id="barDemo">
    @{{#  if(d.status == 'new'){ }}
        @{{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/pay') }}/@{{ d.id }}">{{ trans('app.pay') }}</a>
        @{{#  } else{ }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else if(d.status == 'passed'){ }}
        @{{#  if(d.pay_status == 'unpaid'){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('supplier_bill/pay') }}/@{{ d.id }}">{{ trans('app.pay') }}</a>
        @{{#  } }}
        <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } else if(d.status == 'invalid'){ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } else{ }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    @{{#  } }}
</script>

<script>
    var main_url = "{{guard_url('supplier_bill')}}";
    var delete_all_url = "{{guard_url('supplier_bill/destroyAll')}}";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('supplier_bill')}}'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'sn',title:'{{ trans('supplier_bill.label.sn') }}', width:180}
                ,{field:'invoice_date',title:'{{ trans('supplier_bill.label.invoice_date') }}',width:140}
                ,{field:'pay_date',title:'{{ trans('supplier_bill.label.pay_date') }}',width:140}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}',width:160}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}',width:160}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}',width:160}
                ,{field:'pay_date',title:'{{ trans('supplier_bill.label.pay_date') }}',width:160}
                ,{field:'total',title:'{{ trans('supplier_bill.label.total') }}',width:160}
                ,{field:'paid_date',title:'{{ trans('supplier_bill.label.paid_date') }}',width:160}
                ,{field:'paid_total',title:'{{ trans('supplier_bill.label.paid_total') }}',width:160}
                ,{field:'status_button',title:'{{ trans('app.status') }}',width:100}
                ,{field:'pay_status_button',title:'{{ trans('app.pay_status') }}',width:100}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:220, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
