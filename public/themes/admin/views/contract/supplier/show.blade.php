<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('contract.index') }}"><cite>{{ trans('contract.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier_contract/'.$contract->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="supplier_name">{{ $supplier->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="supplier_name">{{ $airport->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('contract.label.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $contract->name }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('contract.label.date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="date" id="date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $contract->start_time }} ~ {{ $contract->end_time }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{  trans('contract.label.image') }}</label>
                        {!! $contract->files('images')
                        ->url($contract->getUploadUrl('images'))
                        ->uploaders()!!}
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="supplier">
                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
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

        //执行一个laydate实例
        laydate.render({
            elem: '#date'
            ,type: 'date'
            ,value: '{{ $contract->start_time }} ~ {{ $contract->end_time }}'
            ,range: '~' //或 range: '~' 来自定义分割字符
        });
        form.on('radio(airports)', function(data){
            $("#name").val($("#supplier_name").text()+' - '+data.elem.title);
        });
    });
</script>