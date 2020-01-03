<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a><cite>账单管理</cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ url('/admin/airline/create') }}">添加账单</a></button>
                    <!--<button class="layui-btn layui-btn-primary " data-type="del" data-events="del">删除</button>-->
                </div>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">
                <thead>
                <tr>
                    <th data-field="0" data-unresize="true">
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon layui-icon-ok"></i></div>
                    </th>
                    <th data-field="id">
                        <div class="layui-table-cell laytable-cell-1-id"><span>ID</span><span class="layui-table-sort layui-inline"><i class="layui-edge layui-table-sort-asc"></i><i class="layui-edge layui-table-sort-desc"></i></span></div>
                    </th>
                    <th>名称</th>
                    <th>创建者</th>
                    <th>创建日期</th>
                    <th>金额</th>
                    <th>剩余审核天数</th>
                    <th>状态</th>
                    <th>上次操作人员</th>
                    <th><div class="layui-table-cell laytable-cell-1-score" align="right"><span>操作</span></div></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td data-field="0"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon layui-icon-ok"></i></div></td>
                    <td>2</td>
                    <td>UNIKEN INTERNATIONAL CO.,LIMITED 2019-12-06 供应商账单</td>
                    <td>员工1</td>
                    <td>2019-12-06</td>
                    <td>$3000.00</td>
                    <td>7</td>
                    <th><div class="layui-table-cell laytable-cell-1-0-7">  <button class="layui-btn layui-btn-normal layui-btn-xs">处理中</button>  </div></th>
                    <th>员工1</th>
                    <td data-field="score" align="right" data-off="true">
                        <div class="layui-table-cell laytable-cell-1-score">
                            <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">通过</a>
                            <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="edit">驳回</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td data-field="0"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon layui-icon-ok"></i></div></td>
                    <td>1</td>
                    <td>UNIKEN INTERNATIONAL CO.,LIMITED 2019-12-05 供应商账单</td>
                    <td>员工1</td>
                    <td>2019-12-05</td>
                    <td>$1200.00</td>
                    <td>/</td>
                    <th><div class="layui-table-cell laytable-cell-1-0-7">  <button class="layui-btn layui-btn-danger layui-btn-xs">已驳回</button>  </div></th>
                    <th>超级管理员</th>
                    <td data-field="score" align="right" data-off="true">
                        <div class="layui-table-cell laytable-cell-1-score">

                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>
<!--
<script>
    var main_url = "{{guard_url('airline')}}";
    var delete_all_url = "{{guard_url('airline/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('airline')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'image',title:'图片', width:200,toolbar:'#imageTEM',}
                ,{field:'url',title:'路径', sort: true}
                ,{field:'order',title:'排序', sort: true}
                ,{field:'score',title:'操作', width:200, align: 'right',toolbar:'#barDemo'}
            ]]
            ,id: 'fb-table'
            ,height: 'full-200'
        });
    });
</script>
-->
{!! Theme::partial('common_handle_js') !!}