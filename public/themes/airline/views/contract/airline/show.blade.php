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
                <form class="layui-form" action="{{guard_url('airline_contract/'.$contract->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="airline_name">{{ $airline->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="airline_name">{{ $airport->name }}</p>
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
                        <label class="layui-form-label">{{ trans('contract.label.increase_price') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="increase_price"  lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $contract->increase_price }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{  trans('contract.label.image') }}</label>
                        {!! $contract->files('images')
                        ->url($contract->getFileURL('images'))
                        ->uploaderFile()!!}
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="airline">
                    <input type="hidden" name="airline_id" value="{{ $airline->id }}">
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

        //????????????laydate??????
        laydate.render({
            elem: '#date'
            ,type: 'date'
            ,value: '{{ $contract->start_time }} ~ {{ $contract->end_time }}'
            ,range: '~' //??? range: '~' ????????????????????????
        });
        form.on('radio(airports)', function(data){
            $("#name").val($("#airline_name").text()+' - '+data.elem.title);
        });
    });
</script>