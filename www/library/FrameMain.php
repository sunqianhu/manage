<?php
/**
 * 主框架
 */
namespace library;

use library\Config;
use library\Auth;

class FrameMain{
    /**
     * 得到页面左边菜单节点
     * @access public
     * @param string $active 活跃项
     * @return string 节点
     */
    function getMenu($active = ''){
        $tag = '';
        $liActive = '';
        $appDomain = Config::getOne('app_domain');
        
        $tag .= '<ul>';
        // 系统管理
        if(Auth::isPermission('system')){
            $tag .= '<li>
<a href="javascript:;"><span class="iconfont icon-setup icon"></span><span class="text">系统管理</span><span class="iconfont icon-arrow-left arrow"></span></a>
<ul>';
            // 用户管理
            if(Auth::isPermission('system_user')){
                $tag .= '<li'.($active == 'system_user' ? ' class="active"' : '').'><a href="'.$appDomain.'system/user/index.php"><span class="text">用户管理</span></a></li>';
            }
            
            // 部门管理
            if(Auth::isPermission('system_department')){
                $tag .= '<li'.($active == 'system_department' ? ' class="active"' : '').'><a href="'.$appDomain.'system/department/index.php"><span class="text">部门管理</span></a></li>';
            }
            
            // 角色管理
            if(Auth::isPermission('system_role')){
                $tag .= '<li'.($active == 'system_role' ? ' class="active"' : '').'><a href="'.$appDomain.'system/role/index.php"><span class="text">角色管理</span></a></li>';
            }
            
            // 权限管理
            if(Auth::isPermission('system_permission')){
                $tag .= '<li'.($active == 'system_permission' ? ' class="active"' : '').'><a href="'.$appDomain.'system/permission/index.php"><span class="text">权限管理</span></a></li>';
            }
            
            // 字典管理
            if(Auth::isPermission('system_dictionary')){
                $tag .= '<li'.($active == 'system_dictionary' ? ' class="active"' : '').'><a href="'.$appDomain.'system/dictionary/index.php"><span class="text">字典管理</span></a></li>';
            }
            
            // 登录日志
            if(Auth::isPermission('system_login_log')){
                $tag .= '<li'.($active == 'system_login_log' ? ' class="active"' : '').'><a href="'.$appDomain.'system/login_log/index.php"><span class="text">登录日志</span></a></li>';
            }
            
            // 操作日志
            if(Auth::isPermission('system_operation_log')){
                $tag .= '<li'.($active == 'system_operation_log' ? ' class="active"' : '').'><a href="'.$appDomain.'system/operation_log/index.php"><span class="text">操作日志</span></a></li>';
            }
            
            $tag .= '</ul>
</li>';
            
        }
        $tag .= '</ul>';
        return $tag;
    }
}