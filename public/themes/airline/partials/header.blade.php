<div class="layui-header">
    <ul class="layui-nav layui-layout-right">
        <!-- <li class="layui-nav-item">
          <a href="">控制台<span class="layui-badge">9</span></a>
        </li>
        <li class="layui-nav-item">
          <a href="">个人中心<span class="layui-badge-dot"></span></a>
        </li> -->
        <li class="layui-nav-item" lay-unselect="">
            <a href="{{ guard_url('message') }}" layadmin-event="message" lay-text="消息中心">
                <i class="layui-icon layui-icon-notice"></i>
                <!-- 如果有新消息，则显示小圆点 -->
                @inject('message_repository','App\Repositories\Eloquent\MessageRepository')
                @if($message_repository->unReadCount())
                    <span class="layui-badge-dot"></span>
                @endif
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect="">
            <a href="javascript:;"><img src="http://t.cn/RCzsdCq" class="layui-nav-img">{{ Auth::user()->email }}</a>
            <dl class="layui-nav-child">
                <dd><a href="{{ guard_url('password') }}">修改信息</a></dd>
                <dd><a href="{{ guard_url('logout') }}">退出</a></dd>
            </dl>
        </li>
    </ul>
</div>
