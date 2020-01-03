<div class="layui-form-item level-high">
    <label class="layui-form-label"><?php echo e(trans('app.area')); ?></label>
    <div class="layui-input-inline">
        <select name="country_id" lay-filter="country" id="s_country" lay-verify="required"  lay-search>
            <option value=""><?php echo e(trans('app.select_country')); ?></option>
            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($country->id); ?>" <?php if(isset($country_id) && $country->id == $country_id): ?> selected <?php endif; ?>><?php echo e($country->name_en); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="layui-input-inline">
        <select name="province_id" lay-filter="province" id="s_province" lay-search>
            <option value=""><?php echo e(trans('app.select_province')); ?></option>
        </select>
    </div>
    <div class="layui-input-inline">
        <select name="city_id" lay-filter="city" id="s_city" lay-search>
            <option value=""><?php echo e(trans('app.select_city')); ?></option>
        </select>
    </div>
</div>



<?php if(isset($country_id)): ?>
    <script type="text/javascript">

        layui.use(['form', 'layer', 'laytpl', 'jquery'], function () {
            var form = layui.form, $ = layui.jquery;

            form.on('select(country)', function (data) {

                if(data.id)
                {
                    var p = data.id;
                }else{
                    var p = $("#s_country").val();
                }

                if (p) {
                    layer.load();
                    $.get("<?php echo e(guard_url('world_city/list')); ?>?parent_id=" + p, function (result) {
                        layer.closeAll("loading");
                        var c = result.data;
                        $("#s_province").html("");
                        if(c.length > 0)
                        {
                            $("#s_province").append("<option value=''><?php echo e(trans('app.select_province')); ?></option>");
                            for (v in c) {
                                var cc = c[v].id;
                                if(cc == "<?php echo e($province_id); ?>")
                                {
                                    $("#s_province").append("<option value=" + cc + " selected>" + c[v].name_en + "</option>")
                                    layui.event.call(this,'form','select(province)',{id:"<?php echo e($province_id); ?>"});
                                }else{
                                    $("#s_province").append("<option value=" + cc + ">" + c[v].name_en + "</option>")
                                }
                            }
                        }else{
                            $("#s_province").append("<option value=''>--------</option>");
                            $("#s_city").html("");
                            $("#s_city").append("<option value=''>--------</option>");
                        }
                        form.render();
                    })
                }
            });
            form.on('select(province)', function (data) {
                <?php if(isset($country_id)): ?>
                if(data.id)
                {
                    var p = data.id;
                }else{
                    var p = $("#s_province").val();
                }
                <?php endif; ?>
                if (p) {
                    layer.load();
                    $.get("<?php echo e(guard_url('world_city/list')); ?>?parent_id=" + p, function (result) {
                        layer.closeAll("loading");
                        var c = result.data;
                        $("#s_city").html("");
                        if(c.length > 0) {
                            $("#s_city").append("<option value=''><?php echo e(trans('app.select_city')); ?></option>");
                            for (v in c) {
                                var cc = c[v].id;
                                if(cc == "<?php echo e($city_id); ?>")
                                {
                                    $("#s_city").append("<option value=" + cc + " selected>" + c[v].name_en + "</option>")
                                }else{
                                    $("#s_city").append("<option value=" + cc + ">" + c[v].name_en + "</option>")
                                }
                            }
                        }else {
                            $("#s_city").append("<option value=''>--------</option>");
                        }
                        form.render();
                    })
                }
            });

            <?php if(isset($country_id)): ?>
            layui.event.call(this,'form','select(country)',{id:"<?php echo e($country_id); ?>"});
            <?php endif; ?>

        });


    </script>
<?php else: ?>
    <script type="text/javascript">

        layui.use(['form', 'layer', 'laytpl', 'jquery'], function () {
            var form = layui.form, $ = layui.jquery;

            form.on('select(country)', function (data) {
                var p = $("#s_country").val();
                if (p) {
                    layer.load();
                    $.get("<?php echo e(guard_url('world_city/list')); ?>?parent_id=" + p, function (result) {
                        layer.closeAll("loading");
                        var c = result.data;
                        $("#s_province").html("");
                        if(c.length > 0)
                        {
                            $("#s_province").append("<option value=''><?php echo e(trans('app.select_province')); ?></option>");
                            for (v in c) {
                                var cc = c[v].id;
                                $("#s_province").append("<option value=" + cc + ">" + c[v].name_en + "</option>")
                            }
                        }else{
                            $("#s_province").append("<option value=''>--------</option>");
                            $("#s_city").html("");
                            $("#s_city").append("<option value=''>--------</option>");
                        }
                        form.render();
                    })
                }
            });
            form.on('select(province)', function (data) {
                var p = $("#s_province").val();
                if (p) {
                    layer.load();
                    $.get("<?php echo e(guard_url('world_city/list')); ?>?parent_id=" + p, function (result) {
                        layer.closeAll("loading");
                        var c = result.data;
                        $("#s_city").html("");
                        if(c.length > 0) {
                            $("#s_city").append("<option value=''><?php echo e(trans('app.select_city')); ?></option>");
                            for (v in c) {
                                var cc = c[v].id;
                                $("#s_city").append("<option value=" + cc + ">" + c[v].name_en + "</option>")
                            }
                        }else {
                            $("#s_city").append("<option value=''>--------</option>");
                        }
                        form.render();
                    })
                }
            });
        });


    </script>
<?php endif; ?>