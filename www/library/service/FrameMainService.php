<?php
/**
 * 主框架
 */
namespace library\service;

use library\service\ConfigService;
use library\service\AuthService;
use library\service\TreeService;


class FrameMainService{
    /**
     * 得到页面左边菜单节点
     * @param string $active 活跃项
     * @access public
     * @return string 节点
     */
    static function getPageLeftMenu($active = ''){
        $node = '';
        $liActive = '';
        $appDomain = ConfigService::getOne('app_domain');
        
        $node .= '<ul>';
        // 系统管理
        if(AuthService::isPermission('system')){
            $node .= '<li>
<a href="javascript:;"><span class="iconfont icon-setup icon"></span><span class="text">系统管理</span><span class="iconfont icon-arrow-left arrow"></span></a>
<ul>';
            // 用户管理
            if(AuthService::isPermission('system_user')){
                $node .= '<li'.($active == 'system_user' ? ' class="active"' : '').'><a href="'.$appDomain.'system/user/index.php"><span class="text">用户管理</span></a></li>';
            }
            
            // 部门管理
            if(AuthService::isPermission('system_department')){
                $node .= '<li'.($active == 'system_department' ? ' class="active"' : '').'><a href="'.$appDomain.'system/department/index.php"><span class="text">部门管理</span></a></li>';
            }
            
            // 角色管理
            if(AuthService::isPermission('system_role')){
                $node .= '<li'.($active == 'system_role' ? ' class="active"' : '').'><a href="'.$appDomain.'system/role/index.php"><span class="text">角色管理</span></a></li>';
            }
            
            // 权限管理
            if(AuthService::isPermission('system_permission')){
                $node .= '<li'.($active == 'system_permission' ? ' class="active"' : '').'><a href="'.$appDomain.'system/permission/index.php"><span class="text">权限管理</span></a></li>';
            }
            
            // 字典管理
            if(AuthService::isPermission('system_dictionary')){
                $node .= '<li'.($active == 'system_dictionary' ? ' class="active"' : '').'><a href="'.$appDomain.'system/dictionary/index.php"><span class="text">字典管理</span></a></li>';
            }
            
            // 用户文件
            if(AuthService::isPermission('system_user_file')){
                $node .= '<li'.($active == 'system_user_file' ? ' class="active"' : '').'><a href="'.$appDomain.'system/user_file/index.php"><span class="text">用户文件</span></a></li>';
            }
            
            // 登录日志
            if(AuthService::isPermission('system_login_log')){
                $node .= '<li'.($active == 'system_login_log' ? ' class="active"' : '').'><a href="'.$appDomain.'system/login_log/index.php"><span class="text">登录日志</span></a></li>';
            }
            
            // 操作日志
            if(AuthService::isPermission('system_operation_log')){
                $node .= '<li'.($active == 'system_operation_log' ? ' class="active"' : '').'><a href="'.$appDomain.'system/operation_log/index.php"><span class="text">操作日志</span></a></li>';
            }
            
            $node .= '</ul>
</li>';
            
        }
        $node .= '</ul>';
        return $node;
    }
}