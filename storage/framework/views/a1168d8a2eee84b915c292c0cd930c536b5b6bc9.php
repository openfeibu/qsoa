<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('airline_bill')); ?>"><cite><?php echo e(trans('airline_bill.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline">
                    <input class="layui-input search_key" name="issuing_date" id="issuing_date" placeholder="<?php echo e(trans('airline_bill.label.issuing_date')); ?>" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="sn" id="demoReload" placeholder="<?php echo e(trans('airline_bill.label.sn')); ?>" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="agreement_no" id="demoReload" placeholder="<?php echo e(trans('airline_bill.label.agreement_no')); ?>" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>

<?php echo $__env->make('airline_bill/handle', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script>
    var main_url = "<?php echo e(guard_url('airline_bill')); ?>";
    var index_url = "<?php echo e(guard_url('invalid_airline_bill')); ?>";
    var delete_all_url = "<?php echo e(guard_url('airline_bill/destroyAll')); ?>";
    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;

        table.render({
            elem: '#fb-table'
            ,url: index_url
            ,cols: [[
                {checkbox: true, fixed: 'left'}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'issuing_date',title:'<?php echo e(trans('airline_bill.label.issuing_date')); ?>', width:180}
                ,{field:'sn',title:'<?php echo e(trans('airline_bill.label.sn')); ?>', width:180}
                ,{field:'agreement_no',title:'<?php echo e(trans('airline_bill.label.agreement_no')); ?>', width:180}
                ,{field:'airline_name',title:'<?php echo e(trans('airline.name')); ?>', width:180}
                ,{field:'total',title:'<?php echo e(trans('airline_bill.label.total')); ?>', width:180}
                ,{field:'final_total',title:'<?php echo e(trans('airline_bill.label.final_total')); ?>', width:180}
                ,{field:'status_button',title:'<?php echo e(trans('app.status')); ?>', width:180}
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
        laydate.render({
            elem: '#issuing_date'
            ,type: 'date'
        });
    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>


<?php echo Theme::partial('airline_bill_handle_js'); ?>