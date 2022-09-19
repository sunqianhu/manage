<?php
/**
 * 用户服务
 */
namespace library;

use \library\Db;
use \library\Config;

class User{
    
    /**
     * 得到用户姓名
     * @access public
     * @param int $id 用户id
     * @return string 用户姓名
     */
    static function getName($id){
        $pdo = Db::getInstance();
        $pdoStatement = null;
        $sql = '';
        $data = array();
        $name = '';
                
        $sql = 'select name from user where id = :id';
        $data = array(
            ':id'=>$id
        );
        $pdoStatement = Db::query($pdo, $sql, $data);
        $name = Db::fetchColumn($pdoStatement);
        
        return $name;
    }
    
    /**
     * 得到用户头像url
     * @access public
     * @param string $path 头像路径
     * @return string 头像url
     */
    static function getHeadUrl($path){
        $config = Config::getAll();
        $url = '';
        
        if(empty($path)){
            $url = $config['app_domain'].'image/user_head.png';
        }else{
            $url = $config['user_file_domain'].$path;
        }
        
        return $url;
    }
}
