<?php
/**
 * 认证
 */
namespace app\service;

class AuthService{
    /**
     * 保存token
     * @access public
     * @param array $user 用户记录
     * @return boolean
     */
    static function saveToken($user){
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
     * 销毁token
     * @access public
     * @return boolean
     */
    static function unsetToken(){
        unset($_SESSION['user']);
    }
}