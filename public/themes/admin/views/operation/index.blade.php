<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('operation') }}"><cite>{{ trans('operation.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">

            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">

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
    var main_url = "{{guard_url('operation')}}";
    var delete_all_url = "{{guard_url('operation/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('operation')}}'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'admin_name',title:'{{ trans('admin_user.name') }}'}
                ,{title:'{{ trans('operation.label.able_type') }}',templet:'<div>@{{ d.type_name }}</div>'}
                ,{field:'operationable_id',title:'{{ trans('operation.label.able_id') }}'}
                ,{field:'content',title:'{{ trans('operation.label.content') }}'}
                ,{field:'created_at',title:'{{ trans('app.created_at') }}'}
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