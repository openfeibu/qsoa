<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('supplier')); ?>"><cite><?php echo e(trans('supplier.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="<?php echo e(guard_url('supplier/create')); ?>"><?php echo e(trans('supplier.add')); ?></a></button>
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del"><?php echo e(trans('app.delete')); ?></button>
                </div>
                <div class="layui-inline">
                   <input class="layui-input search_key" name="search_name" id="demoReload" placeholder="<?php echo e(trans('supplier.label.name')); ?>" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="<?php echo e(guard_url('supplier_contract/create?supplier_id=')); ?>{{d.id}}">增加合作机场</a>
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
    var main_url = "<?php echo e(guard_url('supplier')); ?>";
    var delete_all_url = "<?php echo e(guard_url('supplier/destroyAll')); ?>";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '<?php echo e(guard_url('supplier')); ?>'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'<?php echo e(trans('supplier.label.name')); ?>', width:150,edit:'text'}
                ,{field:'leader',title:'<?php echo e(trans('supplier.label.leader')); ?>', width:100,edit:'text'}
                ,{field:'tel',title:'<?php echo e(trans('supplier.label.tel')); ?>', width:100,edit:'text'}
                ,{field:'email',title:'<?php echo e(trans('supplier.label.email')); ?>', width:100,edit:'text'}
                ,{field:'position',title:'<?php echo e(trans('supplier.label.position')); ?>', width:100,edit:'text'}
                ,{field:'area',title:'<?php echo e(trans('app.area')); ?>', width:180}
                ,{field:'can_cooperative_airports',title:'<?php echo e(trans('airline.label.can_cooperative_airport')); ?>', toolbar:'#canCooperativeAirportsTEM', width:200, event: "show_can_cooperative_airports"}
                ,{field:'cooperative_airports',title:'<?php echo e(trans('airline.label.cooperative_airport')); ?>', toolbar:'#cooperativeAirportsTEM', width:200,event:"show_cooperative_airports"}
                ,{field:'score',title:'<?php echo e(trans('app.actions')); ?>', width:260, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '<?php echo e(config('app.limit')); ?>'
            ,height: 'full-200'
            ,done:function () {
                element.init();
            }
        });
    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>