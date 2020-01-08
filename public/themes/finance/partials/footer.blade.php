<footer id="footer">
    <div class="copy">
        © CopyRight © 广东乾盛电子科技有限公司
    </div>
</footer>
<script>
    layui.use(['element','jquery'], function(){
        var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块
        var $ = layui.$;
        //监听导航点击
        element.on('nav(demo)', function(elem){
            //console.log(elem)
            layer.msg(elem.text());
        });

    });

</script>