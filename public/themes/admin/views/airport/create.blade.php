<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airport') }}"><cite>{{ trans('airport.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('airport')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airport.label.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airport.label.code') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="code" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('airport.label.leader') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="leader" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('airport_type.name') }}</label>
                        <div class="layui-input-inline">
                            <select name="airport_type_id">
                                <option value="0">请选择</option>
                                @foreach(app('airport_type_repository')->airport_types() as $key => $airport_type)
                                    <option value="{{ $airport_type->id }}">{{ $airport_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item level-high">
                        <label class="layui-form-label">{{ trans('continent.name') }}</label>
                        <div class="layui-input-inline">
                            <select name="continent_id">
                                <option value="0">请选择</option>
                                @foreach(app('continent')->continents() as $key => $continent)
                                    <option value="{{ $continent->id }}" @if($continent->id == $airport->continent_id) selected @endif >{{ $continent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {!! Theme::widget('area')->render() !!}

                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">{{ trans('airport.label.content') }}</label>
                        <div class="layui-input-block">
                            <script type="text/plain" id="content" name="content" style="width:1000px;height:240px;">

                            </script>
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
