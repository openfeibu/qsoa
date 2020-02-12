<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier_bill_item') }}"><cite>{{ trans('supplier_bill_item.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier_bill_item')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-block">
                            @foreach($airports as $key => $airport)
                                <input type="radio" name="airport_id" title="{{ $airport->name }}"  value="{{ $airport->id }}" lay-filter="airports" lay-verify="otherReq" @if($airport['id'] == $supplier_bill_item['airport_id']) checked @endif >
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airline.name') }}</label>
                        <div class="layui-input-block">
                            @foreach($airlines as $key => $airline)
                                <input type="radio" name="airline_id" title="{{ $airline->name }}"  value="{{ $airline->id }}" lay-filter="airlines" lay-verify="otherReq"  @if($airline['id'] == $supplier_bill_item['airline_id']) checked @endif >
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill_item.label.flight_date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="flight_date" id="flight_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input flight_date" value="{{ $supplier_bill_item['flight_date'] }}">
                        </div>
                    </div>
                    @foreach($supplier_bill_item_info as $key => $field)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ $field['field'] }}{{ $field['field_comment'] ? ($field['field_comment']) : ''}}</label>
                            <div class="layui-input-inline">
                                <input type="text" name="field[{{ $field['supplier_bill_template_field_id'] }}]" autocomplete="off" placeholder="" class="layui-input" value="{{ $field['field_value'] }}">
                            </div>
                        </div>
                    @endforeach
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill_item.label.total') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total" lay-verify="required|number" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier_bill_item['total'] }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
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

        //执行一个laydate实例
        laydate.render({
            elem: '#invoice_date'
            ,type: 'date'
        });
        laydate.render({
            elem: '#flight_date'
            ,type: 'date'
        });

    });
</script>