<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier_bill_item') }}"><cite>{{ trans('supplier_bill_item.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('supplier_bill_item/create') }}">{{ trans('app.add') }}</a></button>
                </div>
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-primary " data-type="add_supplier_bill" data-events="add_supplier_bill">{{ trans('supplier_bill.add') }}</button>
                </div>
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
                    <input class="layui-input search_key" name="flight_date" id="flight_date" placeholder="{{ trans('supplier_bill_item.label.flight_date') }}" autocomplete="off">
                </div>

                <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('supplier_bill_item/create?id=') }}@{{ d.id }}">{{ trans('app.copy') }}</a>
</script>

<script>
    var main_url = "{{guard_url('supplier_bill_item')}}";
    var delete_all_url = "{{guard_url('supplier_bill_item/destroyAll')}}";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('supplier_bill_item')}}'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'flight_date',title:'{{ trans('supplier_bill_item.label.flight_date') }}',width:160}
                ,{field:'supplier_name',title:'{{ trans('supplier.name') }}'}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}'}
                ,{field:'airline_name',title:'{{ trans('airline.name') }}'}
                ,{field:'total',title:'{{ trans('supplier_bill_item.label.total') }}'}
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
            elem: '#flight_date'
            ,type: 'date'
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}
<script>
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        active.add_supplier_bill = function(){
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = 'supplier_bill/create';
            var paramStr = "";
            if(data.length == 0)
            {
                layer.msg('请选择数据', {
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                })
                return false;
            }
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?supplier_bill_item_ids[]="+v.id;
                }else{
                    paramStr += "&supplier_bill_item_ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            window.location.href=url+paramStr;
        }
    });
</script>