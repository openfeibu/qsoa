<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('contract.index') }}"><cite>{{ trans('contract.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier_contract')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p" id="supplier_name">{{ $supplier->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport.name') }}</label>
                        <div class="layui-input-block">
                            <!--
                            <select name="airport_id"  lay-search lay-verify="required">
                                @foreach($airports as $key => $airport)
                                    @if(in_array($airport->id,$exist_airport_ids))
                                        <option value="{{ $airport->id }}" disabled>{{ $airport->name }}  ({{ trans('app.have_cooperation') }})</option>
                                    @else
                                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            -->
                            @foreach($airports as $key => $airport)
                                <div class="layui-col-sm4">
                                @if(in_array($airport->id,$exist_airport_ids))
                                    <input type="radio" name="airport_id" title="{{ $airport->name }} ({{ trans('app.have_cooperation') }})"  value="{{ $airport->id }}" lay-filter="airports" lay-verify="required" disabled >
                                @else
                                    <input type="radio" name="airport_id" title="{{ $airport->name }}"  value="{{ $airport->id }}" lay-filter="airports" lay-verify="required" >
                                @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('contract.label.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('contract.label.date') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="date" id="date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{  trans('contract.label.image') }}</label>
                        {!! $contract->files('images')
                        ->url($contract->getUploadUrl('images'))
                        ->deleteUrl(guard_url('contract/destroy_image'))
                        ->uploaders()!!}
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            @if(count($airports) == count($exist_airport_ids))
                                <button class="layui-btn layui-btn-disabled " lay-submit="" lay-filter="demo1"  disabled >{{ trans('app.submit_now') }}</button>
                            @else
                                <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="type" value="supplier">
                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
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
            elem: '#date'
            ,type: 'date'
            ,range: '~' //或 range: '~' 来自定义分割字符
        });
        form.on('radio(airports)', function(data){
            $("#name").val($("#supplier_name").text()+' - '+data.elem.title);
        });
    });
</script>