<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airline') }}"><cite>{{ trans('airline.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('airline/'.$airline->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline->name }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.leader') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="leader" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline->leader }}" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.tel') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="tel" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline->tel }}" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.email') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" lay-verify="required|email" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline->email }}" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.position') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="position" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{ $airline->position }}">
                        </div>
                    </div>

                    {!! Theme::widget('area',['country_id' => $airline->country_id,'province_id' => $airline->province_id,'city_id' => $airline->city_id ])->render() !!}

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airline.label.address') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="address"  autocomplete="off" placeholder="" class="layui-input"  value="{{ $airline->address }}">
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airline.label.can_cooperative_airport') }}</label>
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

