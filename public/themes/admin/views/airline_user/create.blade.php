<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('airline_user') }}"><cite>{{ trans("airline_user.title") }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}</cite></a>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            {!! Theme::partial('message') !!}
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('airline_user')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("airline_user.name") }}</label>
                        <div class="layui-input-inline">
                            <select name="airline_id">
                                @foreach($airlines as $key => $airline)
                                    <option value="{{ $airline->id }}" @if($airline->id == $airline_user->airline_id) selected @endif>{{ $airline->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("airline_user.label.email") }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" value="{{ $airline_user->email }}" lay-verify="email|required" autocomplete="off" placeholder="请输入{{ trans("airline_user.label.email") }}" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("airline_user.label.name") }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" value="{{ $airline_user->name }}" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans("airline_user.label.name") }}" class="layui-input" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("airline_user.label.password") }}</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" placeholder="请输入{{ trans("airline_user.label.password") }}" autocomplete="off" class="layui-input"  lay-verify="required" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">请输入密码，至少六位数</div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("airline_user.label.roles") }}</label>
                        <div class="layui-input-block">
                            <?php $i=1 ?>
                            @foreach($roles as $key => $role)
                            <input type="radio" name="roles[]" value="{{ $role->id }}" title="{{ $role->name }}" @if($i == 1) checked @endif >
                             <?php $i++ ?>
                            @endforeach
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    layui.use('form', function(){
        var form = layui.form;

        form.render();
    });
</script>

