<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airline_bill') }}"><cite>{{ trans('airline_bill.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('airline_bill')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.agreement_no') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="agreement_no" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.issuing_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="issuing_date" id="issuing_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.date_of_supply') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="date_of_supply" id="date_of_supply" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.total') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $total }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.final_total') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="final_total" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $final_total }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline_bill.label.pay_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="pay_date" id="pay_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                    <table lay-filter="airline_bill_item" id="airline_bill_item">
                        <thead>
                        <tr>
                            <th lay-data="{field:'id',hide:true}">ID</th>
                            <th lay-data="{field:'airport_name'}">{{ trans('airport.name') }}</th>
                            <th lay-data="{field:'date'}">{{ trans('airline_bill_item.label.date') }}</th>
                            <th lay-data="{field:'usg'}">{{ trans('airline_bill_item.label.usg') }}</th>
                            <th lay-data="{field:'price'}">{{ trans('airline_bill_item.label.price') }}</th>
                            <th lay-data="{field:'sum'}">{{ trans('airline_bill_item.label.sum') }}</th>
                            <th lay-data="{field:'tax',edit:'text'}">{{ trans('airline_bill_item.label.tax') }}</th>
                            <th lay-data="{field:'incl_tax',edit:'text'}">{{ trans('airline_bill_item.label.incl_tax') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($supplier_bill_items as $key => $supplier_bill_item)
                        <tr>
                            <td>{{ $supplier_bill_item['id'] }}</td>
                            <td>{{ $supplier_bill_item['airport_name'] }}</td>
                            <td>{{ $supplier_bill_item['flight_date'] }}</td>
                            <td>{{ $supplier_bill_item['airline_bill_item']['usg'] }}</td>
                            <td>{{ $supplier_bill_item['airline_bill_item']['price'] }}</td>
                            <td>{{ $supplier_bill_item['airline_bill_item']['sum'] }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" type="button" lay-submit="" lay-filter="airline_bill_item_create">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>

                    {!!Form::token()!!}
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
        table.init('airline_bill_item', {

        });
        //执行一个laydate实例
        laydate.render({
            elem: '#issuing_date'
            ,type: 'date'
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
        });
        laydate.render({
            elem: '#date_of_supply'
            ,type: 'date'
            ,range:'~'
        });

        form.on('submit(airline_bill_item_create)', function(){
            var data = layui.table.cache.airline_bill_item;
            var form_data = {};
            var date = [],usg = [],price = [],sum = [],tax = [],incl_tax = [],supplier_bill_item_ids = [];
            $.each(data, function(i, val) {
                supplier_bill_item_ids[i] = val.id;
                date[i] = val.date;
                usg[i] = val.usg;
                price[i] = val.price;
                sum[i] = val.sum;
                tax[i] = val.tax;
                incl_tax[i] = val.incl_tax;
            });
            var agreement_no = $("input[name='agreement_no']").val();
            var issuing_date = $("input[name='issuing_date']").val();
            var pay_date = $("input[name='pay_date']").val();
            var date_of_supply = $("input[name='date_of_supply']").val();
            var total = $("input[name='total']").val();
            var final_total = $("input[name='final_total']").val();

            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('airline_bill') }}",
                data : {'supplier_bill_id':'{{ $supplier_bill_id }}','supplier_bill_item_ids':supplier_bill_item_ids,'date':date,'usg':usg,'price':price,'sum':sum,'tax':tax,'incl_tax':incl_tax,'agreement_no':agreement_no,'issuing_date':issuing_date,'date_of_supply':date_of_supply,'total':total,'final_total':final_total,'pay_date':pay_date,'_token':"{!! csrf_token() !!}"},
                type : 'POST',
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