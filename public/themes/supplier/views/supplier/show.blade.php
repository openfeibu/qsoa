<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier') }}"><cite>{{ trans('supplier.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier/'.$supplier->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.label.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier->name }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.label.leader') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="leader" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier->leader }}" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.label.tel') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="tel" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier->tel }}" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.label.email') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" lay-verify="required|email" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier->email }}" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.label.position') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="position" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier->position }}">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('supplier.label.day_consume') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="day_consume" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $supplier->day_consume }}">
                        </div>
                    </div>

                    {!! Theme::widget('area',['country_id' => $supplier->country_id,'province_id' => $supplier->province_id,'city_id' => $supplier->city_id ])->render() !!}

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.address') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="address"  autocomplete="off" placeholder="" class="layui-input"  value="{{ $supplier->address }}">
                        </div>
                    </div>

                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('supplier.label.can_cooperative_airport') }}</label>
                        <div class="layui-input-inline">
                            @foreach($airports as $key => $airport)
                                <input type="checkbox" name="airports[]" title="{{ $airport->name }}"  value="{{ $airport->id }}" @if(in_array($airport->id ,$can_cooperative_airport_ids)) checked @endif>
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
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

