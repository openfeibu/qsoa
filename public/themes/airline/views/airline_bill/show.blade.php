<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('new_airline_bill') }}"><cite>{{ trans('airline_bill.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('airline_bill/'.$airline_bill->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="airport_id" value="{{ $airline_bill->airport_id }}">
                            <p class="input-p">{{ $airline_bill->airport_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airline.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="airline_id" value="{{ $airline_bill->airline_id }}">
                            <p class="input-p">{{ $airline_bill->airline_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('supplier.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="hidden" name="supplier_id" value="{{ $airline_bill->supplier_id }}">
                            <p class="input-p">{{ $airline_bill->supplier_name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.agreement_no') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="agreement_no" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline_bill['agreement_no'] }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.issuing_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="issuing_date" id="issuing_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline_bill['issuing_date'] }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.date_of_supply') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill['supply_start_date'] }} ~ {{ $supplier_bill['supply_end_date'] }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.total') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ number_format($supplier_bill->total,3) }}</p>
                        </div>
                    </div>
                    <!--
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('contract.label.increase_price') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $contract['increase_price'] }}</p>
                        </div>
                    </div>
                    -->
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.total') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ number_format($airline_bill->total,3) }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.pay_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="pay_date" id="pay_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline_bill['pay_date'] }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <table lay-filter="airline_bill" id="airline_bill">
                            <thead>
                                <tr>
                                    <th lay-data="{field:'date',width:220}">DATE 日期</th>
                                    <th lay-data="{field:'airport_name'}">Airport 机场</th>
                                    <th lay-data="{field:'usg'}">加油量（L）</th>
                                    <th lay-data="{field:'price'}">Price单价（USD/USG）</th>
                                    <th lay-data="{field:'total'}">Sum总金额（美元）</th>
                                    <th lay-data="{field:'tax',width:100}">Tax</th>
                                    <th lay-data="{field:'incl_tax'}">Incl.Tax USD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $supplier_bill['supply_start_date'] }} ~ {{ $supplier_bill['supply_end_date'] }}</td>
                                    <td>{{ $airline_bill['airport_name'] }}</td>
                                    <td>{{ $airline_bill['litre'] }}</td>
                                    <td>{{ $airline_bill['price'] }}</td>
                                    <td>{{ $airline_bill['total'] }}</td>
                                    <td>{{ $airline_bill['tax'] }}</td>
                                    <td>{{ $airline_bill['incl_tax'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-form-item">
                    <table lay-filter="airline_bill_item" id="airline_bill_item">
                        <thead>
                        <tr>
                            <th lay-data="{field:'id', fixed: 'left', totalRowText: 'Total：',width:80}">ID</th>
                            <th lay-data="{field:'flight_date'}">{{ trans('airline_bill_item.label.flight_date') }}</th>
                            @foreach($fields as $key => $field)
                                <th lay-data="{field:'{{ $field }}'}">{{ $field }}</th>
                            @endforeach
                            <th lay-data="{field:'litre', totalRow: true,toFixed:3}">{{ trans('airline_bill_item.label.litre') }}</th>
                            <th lay-data="{field:'mt', totalRow: true,toFixed:3}">{{ trans('airline_bill_item.label.mt') }}</th>
                            <th lay-data="{field:'usg', totalRow: true,toFixed:3}">{{ trans('airline_bill_item.label.usg') }}</th>
                            <th lay-data="{field:'unit'}">{{ trans('airline_bill_item.label.unit') }}</th>
                            <th lay-data="{field:'price'}">{{ trans('airline_bill_item.label.price') }}</th>
                            <th lay-data="{field:'total', totalRow: true,toFixed:3}">{{ trans('airline_bill_item.label.total') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($airline_bill_items as $key => $airline_bill_item)
                            <tr>
                                <td>{{ $airline_bill_item['id'] }}</td>
                                <td>{{ $airline_bill_item['flight_date'] }}</td>
                                @foreach($airline_bill_item['fields'] as $k => $field)
                                    <td>{{ $field['field_value'] }}</td>
                                @endforeach
                                <td>{{ $airline_bill_item['litre'] }}</td>
                                <td>{{ $airline_bill_item['mt'] }}</td>
                                <td>{{ $airline_bill_item['usg'] }}</td>
                                <td>{{ $airline_bill_item['unit'] }}</td>
                                <td>{{ $airline_bill_item['price'] }}</td>
                                <td>{{ $airline_bill_item['total'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    @if(in_array($airline_bill->status,['new','rejected','modified']) )
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" type="button" lay-submit="" lay-filter="airline_bill_item_create">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    @endif
                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>
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
        table.init('airline_bill', {
            cellMinWidth :'140'
            ,done:function(res, curr, count) {

            }
        });
        table.init('airline_bill_item', {
            cellMinWidth :'180'
            ,totalRow: true //开启合计行
            ,done:function(res, curr, count) {
                var total = $('#airline_bill_item').parent().find(".layui-table-total").find("td[data-field='total']").find("div").text("{{ $airline_bill['total'] }}")
                var mt = $('#airline_bill_item').parent().find(".layui-table-total").find("td[data-field='mt']").find("div").text("{{ $airline_bill['mt'] }}")
                var usg = $('#airline_bill_item').parent().find(".layui-table-total").find("td[data-field='usg']").find("div").text("{{ $airline_bill['usg'] }}")

            }
        });
        //执行一个laydate实例
        laydate.render({
            elem: '#issuing_date'
            ,type: 'date'
            ,value: "{{ $airline_bill['issuing_date'] }}"
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
            ,value: "{{ $airline_bill['pay_date'] }}"
        });

        form.on('submit(airline_bill_item_create)', function(){
            var data = layui.table.cache.airline_bill_item;
            var form_data = {};
            var date = [],usg = [],price = [],sum = [],tax = [],incl_tax = [],airline_bill_item_ids = [];
            $.each(data, function(i, val) {
//                airline_bill_item_ids[i] = val.id;
//                date[i] = val.date;
//                usg[i] = val.usg;
//                price[i] = val.price;
//                sum[i] = val.sum;
//                tax[i] = val.tax;
//                incl_tax[i] = val.incl_tax;
            });
            var agreement_no = $("input[name='agreement_no']").val();
            var issuing_date = $("input[name='issuing_date']").val();
            var pay_date = $("input[name='pay_date']").val();
//            var date_of_supply = $("input[name='date_of_supply']").val();
//            var total = $("input[name='total']").val();
//            var final_total = $("input[name='final_total']").val();
            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('airline_bill/'.$airline_bill->id) }}",
                data : {'agreement_no':agreement_no,'issuing_date':issuing_date,'pay_date':pay_date,'_token':"{!! csrf_token() !!}"},
                type : 'PUT',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0)
                    {
                        window.location.href = data.url;
                    }else{
                        layer.msg(data.message);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
            return false;
        });
    });
</script>