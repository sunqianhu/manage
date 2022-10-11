<?php
/**
 * 用户
 */
namespace library;

use library\DbHelper;
use library\Config;
use library\Dictionary;

class User{
    
    /**
     * 得到用户姓名
     * @access public
     * @param Integer $id 用户id
     * @return String 用户姓名
     */
    function getName($id){
        $dbHelper = new DbHelper();
$pdo = $dbHelper->getInstance();
        $pdoStatement = null;
        $sql = '';
        $data = array();
        $name = '';
                
        $sql = 'select name from user where id = :id';
        $data = array(
            ':id'=>$id
        );
        $pdoStatement = $dbHelper->query($pdo, $sql, $data);
        $name = $dbHelper->fetchColumn($pdoStatement);
        
        return $name;
    }
    
    /**
     * 得到用户头像url
     * @access public
     * @param String $path 头像路径
     * @return String 头像url
     */
    function getHeadUrl($path){
        $config = Config::getAll();
        $url = '';
        
        if(empty($path)){
            $url = $config['app_domain'].'image/user_head.png';
        }else{
            $url = $config['user_file_domain'].$path;
        }
        
        return $url;
    }
    
    /**
     * 得到徽章状态
     * @access public
     * @param Integer $statusId 状态ID
     * @return String 徽章
     */
    function getBadgeStatusName($statusId){
        $statusName = '';
        $class = '';
        $node = '';
        $dictionary = new Dictionary();
        
        $statusName = $dictionary->getValue('system_user_status', $statusId);
        switch($statusId){
            case 2:
                $class = 'orange';
            break;
        }
        $node = '<span class="sun-badge '.$class.'">'.$statusName.'</span>';
        
        return $node;
    }
}
