<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier_bill') }}"><cite>{{ trans('supplier_bill.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier_bill')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('supplier.name') }}</label>
                        <div class="layui-input-inline">
                            @foreach($suppliers as $key => $supplier)
                                <input type="radio" name="supplier_id" title="{{ $supplier->name }}"  value="{{ $supplier->id }}" lay-filter="suppliers" lay-verify="otherReq" >
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-inline">
                            @foreach($airports as $key => $airport)
                                <input type="radio" name="airport_id" title="{{ $airport->name }}"  value="{{ $airport->id }}" lay-filter="airports" lay-verify="otherReq" >
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airline.name') }}</label>
                        <div class="layui-input-inline">
                            @foreach($airlines as $key => $airline)
                                <input type="radio" name="airline_id" title="{{ $airline->name }}"  value="{{ $airline->id }}" lay-filter="airlines" lay-verify="otherReq" >
                            @endforeach
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
                        <label class="layui-form-label">{{ trans('supplier_bill.label.total') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total" lay-verify="required|number" autocomplete="off" placeholder="" class="layui-input" value="" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{  trans('supplier_bill.label.file') }}</label>
                        {!! $supplier_bill->files('file')
                        ->url($supplier_bill->getFileURL('file'))
                        ->uploaderFile()!!}
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

<script>
    layui.use(['jquery','element','table','laydate'], function(){
        var laydate = layui.laydate;
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        laydate.render({
            elem: '#invoice_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d') !!}"
        });
        laydate.render({
            elem: '#pay_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d') !!}"
        });

        laydate.render({
            elem: '#date_of_supply'
            ,type: 'date'
            ,range:'~'
        });
    });
</script>

