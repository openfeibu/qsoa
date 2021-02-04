<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('quotation') }}"><cite>{{ trans('quotation.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">

                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('quotation/create') }}">{{ trans('app.add') }}</a></button>
                </div>
                <div class="layui-inline">
                    <select name="airport_id" class="layui-select search_key" lay-search>
                        <option value="">{{ trans('airport.name') }}</option>
                        @foreach($airports as $key => $airport)
                            <option value="{{ $airport['id'] }}">{{ $airport['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="name" id="demoReload" placeholder="{{ trans('quotation.label.name') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="billing_date" id="billing_date" placeholder="{{ trans('app.billing_date') }}" autocomplete="off">
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
</script>

<script>
    var main_url = "{{guard_url('quotation')}}";
    var delete_all_url = "{{guard_url('quotation/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('quotation')}}'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'{{ trans('quotation.label.name') }}',edit:'text'}
                ,{field:'airport_name',title:'{{ trans('airport.name') }}',sort:true}
                ,{field:'billing_date',title:'{{ trans('app.billing_date') }}',edit:'text'}
                ,{field:'file',title:'{{ trans('supplier_bill.label.file') }}',width:100,templet:'<div><a type="button" class="layui-btn layui-btn-normal layui-btn-xs" href="{{ url('image/original') }}/@{{ d.file }}" target="_blank">{{ trans('app.preview') }}</a></div>'}
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
            elem: '#billing_date'
            ,type: 'month'
        });

    });
</script>

{!! Theme::partial('common_handle_js') !!}