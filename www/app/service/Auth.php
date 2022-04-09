<?php
/**
 * 认证
 */
namespace app\service;

class Auth{
    /**
     * 保存登录
     * @access public
     * @param array $user 用户记录
     * @return boolean
     */
    static function saveSessionUser($user){
        $_SESSION['user'] = $user;
    }
    
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
     * 销毁用户
     * @access public
     * @return boolean
     */
    static function unsetSessionUser(){
        unset($_SESSION['user']);
    }
}