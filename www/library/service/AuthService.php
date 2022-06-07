<?php
/**
 * 认证
 */
namespace library\service;

class AuthService{
    /**
     * 是否登录
     * @access public
     * @return boolean
     */
    static function isLogin(){
        if(empty($_SESSION['user'])){
            return false;
        }
        
        return true;
    }
 
    /**
     * 是否有权限
     * @access public
     * @param string $key 权限key
     * @return boolean
     */
    static function isPermission($key){
        $menus = $_SESSION['menu'];
        $permissions = array();
        
        if(empty($menus)){
            return false;
        }
        
        $permissions = array_column($menus, 'permission');
        $permissions = array_filter($permissions);
        if(empty($permissions)){
            return false;
        }
        if(!in_array($key, $permissions)){
            return false;
        }
        
        return true;
    }
}