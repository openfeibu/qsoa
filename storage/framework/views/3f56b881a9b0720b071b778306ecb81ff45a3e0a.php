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
                            if(cc == "<?php echo e($airport->province_id); ?>")
                            {
                                $("#s_province").append("<option value=" + cc + " selected>" + c[v].name_en + "</option>")
                                layui.event.call(this,'form','select(province)',{id:"<?php echo e($airport->province_id); ?>"});
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
            if(data.id)
            {
                var p = data.id;
            }else{
                var p = $("#s_province").val();
            }
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
                            if(cc == "<?php echo e($airport->city_id); ?>")
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

        layui.event.call(this,'form','select(country)',{id:"<?php echo e($airport->country_id); ?>"});

    });


</script>