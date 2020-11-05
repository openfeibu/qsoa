<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('jinqi') }}"><cite>{{ trans('jinqi.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('jinqi/create') }}">{{ trans('app.add') }}</a></button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="FDate" id="date" placeholder="{{ trans('jinqi.label.FDate') }}" autocomplete="off" style="width: 200px;">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="symbolChnName" placeholder="{{ trans('jinqi.label.symbolChnName') }}" autocomplete="off" style="width: 200px;">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="symbolName" placeholder="{{ trans('jinqi.label.symbolName') }}" autocomplete="off" style="width: 200px;">
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

<script>
    var main_url = "{{guard_url('jinqi')}}";
    var delete_all_url = "{{guard_url('jinqi/destroyAll')}}";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('jinqi')}}'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'FDate',title:'{{ trans('jinqi.label.FDate') }}'}
                ,{field:'symbolCode',title:'{{ trans('jinqi.label.symbolCode') }}'}
                ,{field:'symbolName',title:'{{ trans('jinqi.label.symbolName') }}'}
                ,{field:'symbolChnName',title:'{{ trans('jinqi.label.symbolChnName') }}'}
                ,{field:'FOpen',title:'{{ trans('jinqi.label.FOpen') }}'}
                ,{field:'FHigh',title:'{{ trans('jinqi.label.FHigh') }}'}
                ,{field:'FLow',title:'{{ trans('jinqi.label.FLow') }}'}
                ,{field:'FClose',title:'{{ trans('jinqi.label.FClose') }}'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,done:function () {
                element.init();
            }
        });
        //年月范围选择
        laydate.render({
            elem: '#date'
            ,range: '~' //或 range: '~' 来自定义分割字符
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}