<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airport') }}"><cite>{{ trans('airport.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message layui-from">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('airport/create') }}">{{ trans('app.add') }}</a></button>
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="name" placeholder="{{ trans('airport.label.name') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="code" placeholder="{{ trans('airport.label.code') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <select name="continent_id" class="layui-select search_key">
                        <option value="">{{ trans('continent.name') }}</option>
                        @foreach(app('continent')->continents() as $key => $continent)
                            <option value="{{ $continent->id }}">{{ $continent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="airport_type_id" class="layui-select search_key">
                        <option value="">{{ trans('airport_type.name') }}</option>
                        @foreach(app('airport_type_repository')->airport_types() as $key => $airport_types)
                            <option value="{{ $airport_types->id }}">{{ $airport_types->name }}</option>
                        @endforeach
                    </select>
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
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>

<script>
    var main_url = "{{guard_url('airport')}}";
    var delete_all_url = "{{guard_url('airport/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('airport')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'{{ trans('airport.label.name') }}',edit:'text',width:220}
                ,{field:'continent_name',title:'{{ trans('continent.label.name') }}',width:180}
                ,{field:'airport_type_name',title:'{{ trans('airport_type.name') }}',width:180}
                ,{field:'code',title:'{{ trans('airport.label.code') }}',edit:'text',width:150}
                ,{field:'leader',title:'{{ trans('airport.label.leader') }}',edit:'text',width:150}
                ,{field:'area',title:'{{ trans('app.area') }}'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:200, align: 'right',fixed: 'right',toolbar:'#barDemo'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}

