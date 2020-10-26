<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('airline_bill_template_field.index') }}"><cite>{{ trans('airline_bill_template.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm " data-type="add" data-events="add">{{ trans('app.add') }}</button>
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                </div>

            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<div class="add_content" style="display: none">
    <form class="layui-form add_form" action="" style="margin: 10px 10px ">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field') }}*</label>
            <div class="layui-input-inline">
                <input type="text" name="field" required  lay-verify="required" placeholder="{{ trans('app.must') }}" autocomplete="off" class="layui-input field">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field_comment') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="field_comment" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input field_comment">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field_default') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="field_default" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input field_default">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field_mark') }}</label>
            <div class="layui-input-inline">
                <select name="field_mark" class="layui-select search_key">
                    <option value="">{{ trans('app.empty') }}</option>
                    @foreach(trans('airline_bill_template_field.field_mark') as $key => $field_mark)
                        <option value="{{ $field_mark }}">{{ $field_mark }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('app.label.order') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="order" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input order" value="50">
            </div>
        </div>
    </form>
</div>
<div class="edit_content" style="display: none">
    <form class="layui-form edit_form" action="" style="margin: 10px 10px ">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field') }}*</label>
            <div class="layui-input-inline">
                <input type="text" name="field" required  lay-verify="required" placeholder="{{ trans('app.must') }}" autocomplete="off" class="layui-input field">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field_comment') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="field_comment" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input field_comment">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field_default') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="field_default" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input field_default">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('airline_bill_template_field.label.field_mark') }}</label>
            <div class="layui-input-inline">
                <select name="field_mark" class="layui-select search_key">
                    <option value="">{{ trans('app.empty') }}</option>
                    @foreach(trans('airline_bill_template_field.field_mark') as $key => $field_mark)
                        <option value="{{ $field_mark }}">{{ $field_mark }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('app.label.order') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="order" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input order" >
            </div>
        </div>
    </form>
</div>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="ajax_edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>


<script>
    var main_url = "{{guard_url('airline_bill_template_field')}}";
    var delete_all_url = "{{guard_url('airline_bill_template_field/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: main_url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'field',title:'{{ trans('airline_bill_template_field.label.field') }}',edit:'text'}
                ,{field:'field_comment',title:'{{ trans('airline_bill_template_field.label.field_comment') }}',edit:'text'}
                ,{field:'field_default',title:'{{ trans('airline_bill_template_field.label.field_default') }}',edit:'text'}
                ,{field:'field_mark',title:'{{ trans('airline_bill_template_field.label.field_mark') }}'}
                ,{field:'order',title:'{{ trans('app.label.order') }}',edit:'text'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:260, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: false
            ,height: 'full-200'
            ,done:function () {
                element.init();
            }
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}

<script>
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        active.add = function(){
            layer.open({
                type: 1,
                shade: false,
                title: '{{ trans('app.add') }}', //不显示标题
                area: ['420px', '480px'], //宽高
                content: $('.add_content'),
                btn:['{{ trans('app.submit') }}'],
                btn1:function()
                {
                    var load = layer.load();
                    var form = $('.add_form');
                    var form_data = form.serialize();
                    form_data = form_data+"&_token={!! csrf_token() !!}";
                    $.ajax({
                        url : "{{ guard_url('airline_bill_template_field') }}",
                        data : form_data,
                        type : 'POST',
                        success : function (data) {
                            layer.closeAll();
                            if(data.code == 0) {
                                var nPage = $(".layui-laypage-curr em").eq(1).text();
                                //执行重载
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }else{
                                layer.msg(data.message);
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            layer.msg('{{ trans('messages.server_error') }}');
                        }
                    });
                }
            });

        }
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            var nPage = $(".layui-laypage-curr em").eq(1).text();
            if(obj.event === 'ajax_edit'){
                var edit_content = $(".edit_content");
                edit_content.find("input[name='field']").val(data.field);
                edit_content.find("input[name='field_comment']").val(data.field_comment);
                edit_content.find("input[name='field_default']").val(data.field_default);
                edit_content.find("select[name='field_mark']").val(data.field_mark);
                edit_content.find("input[name='order']").val(data.order);
                form.render();
                layer.open({
                    type: 1,
                    shade: false,
                    title: '{{ trans('app.add') }}', //不显示标题
                    area: ['420px', '480px'], //宽高
                    content: $('.edit_content'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {
                        var load = layer.load();
                        var form = $('.edit_form');
                        var form_data = form.serialize();
                        form_data = form_data+"&_token={!! csrf_token() !!}";
                        console.log(data)
                        $.ajax({
                            url : "{{ guard_url('airline_bill_template_field') }}/"+data.id,
                            data : form_data,
                            type : 'PUT',
                            success : function (data) {
                                layer.closeAll();
                                if(data.code == 0) {
                                    var nPage = $(".layui-laypage-curr em").eq(1).text();
                                    //执行重载
                                    table.reload('fb-table', {
                                        page: {
                                            curr: nPage //重新从第 1 页开始
                                        }
                                    });
                                }else{
                                    layer.msg(data.message);
                                }
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                layer.msg('{{ trans('messages.server_error') }}');
                            }
                        });
                    }
                });
            }
        }
    });
</script>