<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('exchange_rate') }}"><cite>{{ trans('exchange_rate.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('exchange_rate/create') }}">{{ trans('app.add') }}</a></button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="date" id="date" placeholder="{{ trans('exchange_rate.label.date') }}" autocomplete="off" style="width: 200px;">
                </div>
                <div class="layui-inline">
                    <select name="currencyCode" class="layui-select search_key" lay-search>
                        <option value="">{{ trans('exchange_rate.label.currencyCode') }}</option>
                        @foreach($currencies as $key => $currency)
                            <option value="{{ $currency->currencyCode }}">{{ $currency->currencyCode }}</option>
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

<script>
    var main_url = "{{guard_url('exchange_rate')}}";
    var delete_all_url = "{{guard_url('exchange_rate/destroyAll')}}";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('exchange_rate')}}'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'date',title:'{{ trans('exchange_rate.label.date') }}'}
                ,{field:'currencyCode',title:'{{ trans('exchange_rate.label.currencyCode') }}'}
                ,{field:'high',title:'{{ trans('exchange_rate.label.high') }}'}
                ,{field:'low',title:'{{ trans('exchange_rate.label.low') }}'}
                ,{field:'open',title:'{{ trans('exchange_rate.label.open') }}'}
                ,{field:'source',title:'{{ trans('exchange_rate.label.source') }}'}
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