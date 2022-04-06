<?php
/**
 * 认证
 */
namespace app\service;

class Auth{
    /**
     * 保存登录
     * @access public
     * @param array $admin 管理员记录
     * @return boolean
     */
    static function saveSessionAdmin($admin){
        $_SESSION['admin'] = $admin;
    }
    
    /**
     * 是否登录
     * @access public
     * @return boolean
     */
    static function isLogin(){
        if(empty($_SESSION['admin'])){
            return false;
        }
        
        return true;
    }
    
}