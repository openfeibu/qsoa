<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('contract.index')); ?>"><cite><?php echo e(trans('contract.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del"><?php echo e(trans('app.delete')); ?></button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input" name="id" id="demoReload" placeholder="<?php echo e(trans('contract.label.name')); ?>" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit"><?php echo e(trans('app.edit')); ?></a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
</script>
<script type="text/html" id="imageTEM">
    <img src="{{d.image}}" alt="" height="28">
</script>
<script type="text/html" id="canCooperativeAirportsTEM">
    <span class="layui-breadcrumb" lay-separator="|">
    {{#  layui.each(d.can_cooperative_airports, function(index, item){ }}
        <a><cite>{{ item.name }}</cite></a>
        {{#  }); }}
    </span>
</script>
<script type="text/html" id="cooperativeAirportsTEM">
    <span class="layui-breadcrumb" lay-separator="|">
    {{#  layui.each(d.cooperative_airports, function(index, item){ }}
        <a><cite>{{ item.name }}</cite></a>
        {{#  }); }}
    </span>
</script>
<script>
    var main_url = "<?php echo e(guard_url('contract')); ?>";
    var delete_all_url = "<?php echo e(guard_url('contract/destroyAll')); ?>";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        table.render({
            elem: '#fb-table'
            ,url: '<?php echo e(guard_url('contract')); ?>'
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'<?php echo e(trans('contract.label.name')); ?>', edit:'text'}
                ,{field:'airport_name',title:'<?php echo e(trans('airport.name')); ?>', }
                ,{field:'contract_partner',title:'<?php echo e(trans('contract.label.contract_partner')); ?>', }
                ,{field:'start_time',title:'<?php echo e(trans('contract.label.start_time')); ?>', edit:'text'}
                ,{field:'end_time',title:'<?php echo e(trans('contract.label.end_time')); ?>', edit:'text'}
                ,{field:'increase_price',title:'<?php echo e(trans('contract.label.increase_price')); ?>', edit:'text'}
                ,{field:'score',title:'<?php echo e(trans('app.actions')); ?>', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '<?php echo e(config('app.limit')); ?>'
            ,height: 'full-200'
            ,done:function () {
                element.init();
            }
        });
        table.resize('fb-table');

    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>