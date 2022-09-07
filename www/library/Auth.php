<?php
/**
 * 认证
 */
namespace library;

class Auth{
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
     * @param string $tag 权限标识
     * @return boolean
     */
    static function isPermission($tag){
        $permissions = $_SESSION['permission'];
        $tags = array();
        
        if(empty($permissions)){
            return false;
        }
        
        $tags = array_column($permissions, 'tag');
        $tags = array_filter($tags);
        if(empty($tags)){
            return false;
        }
        if(!in_array($tag, $tags)){
            return false;
        }
        
        return true;
    }
}