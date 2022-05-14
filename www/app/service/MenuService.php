<?php
/**
 * 菜单
 */
namespace app\service;

use app\Config;

class MenuService{
    /**
     * 得到html菜单
     * @param string $active 活跃项
     * @access public
     * @return string
     */
    static function getFrameMainHtml($active = ''){
        $config = Config::all();
        $menu = '<div class="menu">
<ul>
<li>
<a href="javascript:;">
<span class="iconfont icon-setup icon"></span>
<span class="text">功能1</span>
<span class="iconfont icon-arrow_left arrow"></span>
</a>
<ul>
<li>
<a href="javascript:;">
<span class="text">功能1_1</span>
<span class="iconfont icon-arrow_left arrow"></span>
</a>
<ul>
<li><a href="#">功能1_1_1</a></li>
<li><a href="#">功能1_1_2</a></li>
</ul>

</li>
<li>
<a href="javascript:;">
<span class="text">功能1_2</span>
<span class="iconfont icon-arrow_left arrow"></span>
</a>
<ul>
<li><a href="#">功能1_2_1</a></li>
<li><a href="#">功能1_2_2</a></li>
</ul>
</li>
<li><a href="#">功能1_3</a></li>
</ul>
</li>
<li>
<a href="javascript:;">
<span class="iconfont icon-setup icon"></span>
<span class="text">系统管理</span>
<span class="iconfont icon-arrow_left arrow"></span>
</a>
<ul>
<li'.($active == 'system_user' ? ' class="active"' : '').'><a href="'.$config['app_domain'].'system/user/index">用户管理</a></li>
<li'.($active == 'system_department' ? ' class="active"' : '').'><a href="'.$config['app_domain'].'system/department/index">部门管理</a></li>
<li><a href="'.$config['app_domain'].'system/role/index">角色管理</a></li>
<li><a href="'.$config['app_domain'].'system/menu/index">菜单管理</a></li>
<li><a href="'.$config['app_domain'].'system/dictionary/index">字典管理</a></li>
<li'.($active == 'system_login_log' ? ' class="active"' : '').'><a href="'.$config['app_domain'].'system/loginLog/index">登录日志</a></li>
<li><a href="'.$config['app_domain'].'system/accessLog/index">访问日志</a></li>
</ul>
</li>
</ul>
</div>';
        return $menu;
    }
}