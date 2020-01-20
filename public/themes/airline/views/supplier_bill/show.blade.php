<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('new_supplier_bill') }}"><cite>{{ trans('supplier_bill.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.details') }}</cite></a>
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
                            <p class="input-p">{!! $supplier_bill->invoice_date !!}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.date_of_supply') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill['supply_start_date'] }} ~ {{ $supplier_bill['supply_end_date'] }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.pay_date') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{!! $supplier_bill->pay_date !!}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier_bill.label.total') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $supplier_bill->total }}</p>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{  trans('supplier_bill.label.file') }}</label>
                        <div class="layui-input-inline">
                            <a type="button" class="layui-btn" href="{!!url("/image/download".$supplier_bill['file'])!!}">{{ trans('app.download') }}</a>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>
    var ue = getUe();
</script>
