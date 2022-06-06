<?php
/**
 * 认证
 */
namespace app\service;

use \app\service\system\UserService;

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
     * 销毁token
     * @access public
     * @return boolean
     */
    static function unsetToken(){
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
    
    /**
     * 得到请求url
     */
    static function getUrl(){
        $uri = '';
        $uris = array();
        $path = '';
        
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            $uri = $_SERVER['REQUEST_URI'];
            $uris = explode('?', $uri);
            $path = $uris[0];
        }
        
        return $path;
    }
    
    /**
     * 是否有权限
     * @access public
     * @return boolean
     */
    static function isPermission(){
        // 排除
        $pathExcludes = array(
            'login/login-*'
        ); // 排除鉴权路径
        $pathAccount = self::getPath();
        $pathUsers = array();
        $pathUser = array();
        
        $pathUsers = UserService::getMenuUrls($_SESSION['user']['id']);
        if(empty($pathUsers)){
            return false;
        }
        
        foreach($pathUsers as $pathUser){
            
            
            
        }
        
        return true;
    }
    
    
    
}