<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier') }}"><cite>{{ trans('supplier.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('supplier/create') }}">{{ trans('supplier.add') }}</a></button>
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                </div>
                <div class="layui-inline">
                   <input class="layui-input search_key" name="search_name" id="demoReload" placeholder="{{ trans('supplier.label.name') }}" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('supplier_contract/create?supplier_id=') }}@{{d.id}}">增加合作机场</a>
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>
<script type="text/html" id="canCooperativeAirportsTEM">
    <span class="layui-breadcrumb" lay-separator="|">
    @{{#  layui.each(d.can_cooperative_airports, function(index, item){ }}
        <a><cite>@{{ item.name }}</cite></a>
        @{{#  }); }}
    </span>
</script>
<script type="text/html" id="cooperativeAirportsTEM">
    <span class="layui-breadcrumb" lay-separator="|">
    @{{#  layui.each(d.cooperative_airports, function(index, item){ }}
        <a><cite>@{{ item.name }}</cite></a>
        @{{#  }); }}
    </span>
</script>

<script>
    var main_url = "{{guard_url('supplier')}}";
    var delete_all_url = "{{guard_url('supplier/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('supplier')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'{{ trans('supplier.label.name') }}', width:150,edit:'text'}
                ,{field:'leader',title:'{{ trans('supplier.label.leader') }}', width:100,edit:'text'}
                ,{field:'tel',title:'{{ trans('supplier.label.tel') }}', width:100,edit:'text'}
                ,{field:'email',title:'{{ trans('supplier.label.email') }}', width:100,edit:'text'}
                ,{field:'position',title:'{{ trans('supplier.label.position') }}', width:100,edit:'text'}
                ,{field:'area',title:'{{ trans('app.area') }}', width:180}
                ,{field:'can_cooperative_airports',title:'{{ trans('airline.label.can_cooperative_airport') }}', toolbar:'#canCooperativeAirportsTEM', width:200, event: "show_can_cooperative_airports"}
                ,{field:'cooperative_airports',title:'{{ trans('airline.label.cooperative_airport') }}', toolbar:'#cooperativeAirportsTEM', width:200,event:"show_cooperative_airports"}
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
    });
</script>

{!! Theme::partial('common_handle_js') !!}