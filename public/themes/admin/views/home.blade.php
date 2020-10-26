<div class="main">
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!-- <div class="layui-card-header">待办事项</div> -->
                <div class="layui-card-body">

                    <div class="fb-carousel fb-backlog " lay-anim="" lay-indicator="inside" lay-arrow="none" >
                        <div carousel-item="">
                            <ul class="layui-row fb-clearfix dataBox layui-col-space5">
                                <li class="layui-col-xs3 ">
                                    <a lay-href="" href="{{ guard_url('airport') }}" class="fb-backlog-body">
                                        <h3>机场总数</h3>
                                        <p><cite>{{ $airport_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" href="{{ guard_url('airline') }}" class="fb-backlog-body">
                                        <h3>航空公司总数</h3>
                                        <p><cite>{{ $airline_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" href="{{ guard_url('supplier') }}" class="fb-backlog-body">
                                        <h3>供应商总数</h3>
                                        <p><cite>{{ $supplier_count }}</cite></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="fb-carousel fb-backlog " lay-anim="" lay-indicator="inside" lay-arrow="none" >
                        <div carousel-item="">
                            <ul class="layui-row fb-clearfix dataBox layui-col-space5">
                                <li class="layui-col-xs3 ">
                                    <a href="{{ guard_url('supplier_bill') }}" class="fb-backlog-body">
                                        <h3>供应商账单总数</h3>
                                        <p><cite>{{ $supplier_bill_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('supplier_bill.status.two-level.new') }}</h3>
                                        <p><cite>{{ $supplier_bill_new_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('supplier_bill.status.two-level.passed') }}</h3>
                                        <p><cite>{{ $supplier_bill_pass_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('supplier_bill.status.two-level.invalid') }}</h3>
                                        <p><cite>{{ $supplier_bill_invalid_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('supplier_bill.status.two-level.bill') }}</h3>
                                        <p><cite>{{ $supplier_bill_bill_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('supplier_bill.status.two-level.finished') }}</h3>
                                        <p><cite>{{ $supplier_bill_finished_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('app.overdue') }}</h3>
                                        <p><cite>{{ $supplier_bill_overdue_count }}</cite></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="fb-carousel fb-backlog " lay-anim="" lay-indicator="inside" lay-arrow="none" >
                        <div carousel-item="">
                            <ul class="layui-row fb-clearfix dataBox layui-col-space5">
                                <li class="layui-col-xs3 ">
                                    <a lay-href="" href="{{ guard_url('airline_bill') }}" class="fb-backlog-body">
                                        <h3>航空公司账单总数</h3>
                                        <p><cite>{{ $airline_bill_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('airline_bill.status.two-level.new') }}</h3>
                                        <p><cite>{{ $airline_bill_new_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('airline_bill.status.two-level.invalid') }}</h3>
                                        <p><cite>{{ $airline_bill_invalid_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('airline_bill.status.two-level.finished') }}</h3>
                                        <p><cite>{{ $airline_bill_finished_count }}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a lay-href="" class="fb-backlog-body">
                                        <h3>{{ trans('app.overdue') }}</h3>
                                        <p><cite>{{ $airline_bill_overdue_count }}</cite></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>