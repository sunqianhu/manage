<?php
/**
 * 框架主要菜单
 */
namespace app\service;

use app\Config;

class FrameMain{
    /**
     * 得到菜单
     * @param string $current 当前功能项
     * @access public
     * @return string
     */
    static function getMenu($current = ''){
        $config = Config::all();
        $menu = '<div class="menu">
<ul>
<li>
<a href="javascript:;">
<span class="iconfont icon-setup icon"></span>
<span class="text">功能栏目1</span>
<span class="iconfont icon-arrow_left arrow"></span>
</a>
<ul>
<li>
<a href="javascript:;">功能1</a>
<ul>
<li><a href="#">功能1_1</a></li>
<li><a href="#">功能1_2</a></li>
</ul>

</li>
<li>
<a href="#">功能2</a>
<ul>
<li><a href="#">功能2_1</a></li>
<li><a href="#">功能2_2</a></li>
</ul>
</li>
<li><a href="#">功能3</a></li>
</ul>
</li>
<li>
<a href="javascript:;">
<span class="iconfont icon-setup icon"></span>
<span class="text">系统管理</span>
<span class="iconfont icon-arrow_left arrow"></span>
</a>
<ul>
<li'.($current == 'system_user' ? ' class="current"' : '').'><a href="'.$config['app_domain'].'user/index">用户管理</a></li>
<li><a href="'.$config['app_domain'].'role/index">角色管理</a></li>
<li><a href="'.$config['app_domain'].'permission/index">权限管理</a></li>
<li><a href="'.$config['app_domain'].'dictionary/index">字典管理</a></li>
<li'.($current == 'system_login_log' ? ' class="current"' : '').'><a href="'.$config['app_domain'].'system/loginLog/index">登录日志</a></li>
<li><a href="'.$config['app_domain'].'accessLog/index">访问日志</a></li>
</ul>
</li>
</ul>
</div>';
        return $menu;
    }
}