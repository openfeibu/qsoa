<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier') }}"><cite>{{ trans('app.top_up') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
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
    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="top_up">{{ trans('app.top_up') }}</a>
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
                ,{field:'name',title:'{{ trans('supplier.name') }}', width:150}
                ,{field:'cooperative_airports',title:'{{ trans('airline.label.cooperative_airport') }}', toolbar:'#cooperativeAirportsTEM', width:200,event:"show_cooperative_airports"}
                ,{field:'used_balance',title:'{{ trans('airport.label.used_balance') }}', width:120}
                ,{field:'balance',title:'{{ trans('airport.label.balance') }}', width:120}
                ,{field:'day_consume',title:'{{ trans('supplier.label.day_consume') }}', width:120}
                ,{field:'balance_day',title:'{{ trans('supplier.label.balance_day') }}', width:120}
                ,{field:'leader',title:'{{ trans('supplier.label.leader') }}', width:100}
                ,{field:'tel',title:'{{ trans('supplier.label.tel') }}', width:120}
                ,{field:'email',title:'{{ trans('supplier.label.email') }}', width:180}
                ,{field:'position',title:'{{ trans('supplier.label.position') }}', width:120}
                ,{field:'area',title:'{{ trans('app.area') }}', width:180}
                ,{field:'can_cooperative_airports',title:'{{ trans('airline.label.can_cooperative_airport') }}', toolbar:'#canCooperativeAirportsTEM', width:200, event: "show_can_cooperative_airports"}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:160, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,done:function () {
                element.init();
            }
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

        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            var nPage = $(".layui-laypage-curr em").eq(1).text();
            if(obj.event === 'top_up'){
                layer.prompt({
                    formType: 0,
                    value: '',
                    title: '{{ trans('app.top_up') }}',
                }, function(value, index, elem){
                    layer.close(index);
                    // ????????????
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('supplier/top_up') }}/"+data.id,
                        data : {'total':value,'_token':"{!! csrf_token() !!}"},
                        type : 'POST',
                        success : function (data) {
                            layer.close(load);
                            var nPage = $(".layui-laypage-curr em").eq(1).text();
                            //????????????
                            table.reload('fb-table', {

                            });
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                });

            }
        }

    });
</script>