<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('message') }}"><cite>{{ trans('message.title') }}</cite></a>
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

<script type="text/html" id="contentTEM">
   @{{#  if(d.read == 0){ }}
   <span class="layui-badge-dot"></span>
   @{{#  } }}
   @{{ d.content }}
</script>

<script>
    var main_url = "{{guard_url('message')}}";
    var delete_all_url = "{{guard_url('message/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('message')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'content',title:'{{ trans('message.label.content') }}',toolbar:'#contentTEM'}
                ,{field:'created_at',title:'{{ trans('app.created_at') }}',width:180}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}

