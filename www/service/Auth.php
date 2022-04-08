<?php
/**
 * 认证
 */
namespace service;

class Auth{
    /**
     * 保存登录
     * @access public
     * @param array $user 管理员记录
     * @return boolean
     */
    static function saveSessionUser($user){
        $_SESSION['user'] = $user;
    }
    
    /**
     * 销毁session user
     * @access public
     * @param array $user 管理员记录
     * @return boolean
     */
    static function unsetSessionUser(){
         unset($_SESSION['user']);
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
    
}