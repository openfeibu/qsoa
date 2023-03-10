<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier_bill') }}"><cite>{{ trans('supplier_bill.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier_bill/'.$supplier_bill['id'])}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="airport_id" value="{{ $supplier_bill->airport_id }}">
                            <p class="input-p">{{ $supplier_bill->airport_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airline.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="airline_id" value="{{ $supplier_bill->airline_id }}">
                            <p class="input-p">{{ $supplier_bill->airline_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('supplier.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="supplier_id" value="{{ $supplier_bill->supplier_id }}">
                            <p class="input-p">{{ $supplier_bill->supplier_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.invoice_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="invoice_date" id="invoice_date"  lay-verify="required" autocomplete="off" placeholder="" class="layui-input invoice_date" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.date_of_supply') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="date_of_supply" id="date_of_supply" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.pay_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="pay_date" id="pay_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <table lay-filter="supplier_bill_item" id="supplier_bill_item">
                            <thead>
                            <tr>
                                <th lay-data="{field:'id', fixed: 'left', totalRowText: 'Total???',width:80}">ID</th>
                                <th lay-data="{field:'flight_date',edit:'text'}">{{ trans('supplier_bill_item.label.flight_date') }}</th>
                                <th lay-data="{field:'flight_number',edit:'text'}">{{ trans('supplier_bill_item.label.flight_number') }}</th>
                                <th lay-data="{field:'board_number',edit:'text'}">{{ trans('supplier_bill_item.label.board_number') }}</th>
                                <th lay-data="{field:'order_number',edit:'text'}">{{ trans('supplier_bill_item.label.order_number') }}</th>
                                <th lay-data="{field:'num_of_orders',edit:'text'}">{{ trans('supplier_bill_item.label.num_of_orders') }}</th>
                                <th lay-data="{field:'mt', totalRow: true,toFixed:3,edit:'text'}">{{ trans('supplier_bill_item.label.mt') }}</th>
                                <th lay-data="{field:'usg', totalRow: true,toFixed:3,edit:'text'}">{{ trans('supplier_bill_item.label.usg') }}</th>
                                <th lay-data="{field:'unit',edit:'text'}">{{ trans('supplier_bill_item.label.unit') }}</th>
                                <th lay-data="{field:'price',edit:'text'}">{{ trans('supplier_bill_item.label.price') }}</th>
                                <th lay-data="{field:'total', totalRow: true,toFixed:3}">{{ trans('supplier_bill_item.label.total') }}</th>
                                <th lay-data="{fixed:'right', align: 'right',toolbar:'#barDemo',width:'100'}">{{ trans('app.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($supplier_bill_items as $key => $supplier_bill_item)
                                <tr>
                                    <td>{{ $supplier_bill_item['id'] }}</td>
                                    <td>{{ $supplier_bill_item['flight_date'] }}</td>
                                    <td>{{ $supplier_bill_item['flight_number'] }}</td>
                                    <td>{{ $supplier_bill_item['board_number'] }}</td>
                                    <td>{{ $supplier_bill_item['order_number'] }}</td>
                                    <td>{{ $supplier_bill_item['num_of_orders'] }}</td>
                                    <td>{{ $supplier_bill_item['mt'] }}</td>
                                    <td>{{ $supplier_bill_item['usg'] }}</td>
                                    <td>{{ $supplier_bill_item['unit'] }}</td>
                                    <td>{{ $supplier_bill_item['price'] }}</td>
                                    <td>{{ $supplier_bill_item['total'] }}</td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.total') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total" lay-verify="required|number" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier_bill->total }}" d>
                        </div>
                    </div>
                    -->

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('supplier_bill_item') }}/@{{ d.id }}?type=bill">{{ trans('app.edit') }}</a>
</script>
{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>
    var ue = getUe();
</script>

<script>

    layui.use(['jquery','element','table','laydate'], function(){
        var laydate = layui.laydate;
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.init('supplier_bill_item', {
            cellMinWidth :'180'
            ,totalRow: true //???????????????
            ,done:function(res, curr, count) {
                var total = $(".layui-table-total").find("td[data-field='total']").find("div").text("{{ $supplier_bill->total }}")
            }
        });

        laydate.render({
            elem: '#invoice_date'
            ,type: 'date'
            ,value:"{!! $supplier_bill->invoice_date !!}"
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
            ,value:"{!! $supplier_bill->pay_date !!}"
        });

        laydate.render({
            elem: '#date_of_supply'
            ,type: 'date'
            ,range:'~'
            ,value: "{{ $supplier_bill['supply_start_date'] }} ~ {{ $supplier_bill['supply_end_date'] }}"
        });

        table.on('edit(supplier_bill_item)', function(obj){
            var data = obj.data;
            var value = obj.value //?????????????????????
                    ,data = obj.data //???????????????????????????
                    ,field = obj.field; //????????????
            var ajax_data = {};
            ajax_data['_token'] = "{!! csrf_token() !!}";
            ajax_data[field] = value;
            // ????????????
            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('supplier_bill_item') }}"+'/'+data.id,
                data : ajax_data,
                type : 'PUT',
                success : function (data) {
                    layer.close(load);
                    window.location.reload();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
        });
    });
</script>
