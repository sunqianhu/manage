<?php
/**
 * 用户模型
 */
namespace library\model;

use library\Config;
use library\DbHelper;
use library\model\Dictionary;

class User{
    
    /**
     * 得到用户姓名
     * @access public
     * @param integer $id 用户id
     * @return string 用户姓名
     */
    function getName($id){
        $dbHelper = new DbHelper();
        $pdo = $dbHelper->getPdo();
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
     * @param string $path 头像路径
     * @return string 头像url
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
     * @param integer $statusId 状态ID
     * @return string 徽章
     */
    function getBadgeStatusName($statusId){
        $statusName = '';
        $class = '';
        $tag = '';
        $dictionaryModel = new Dictionary();
        
        $statusName = $dictionaryModel->getValue('system_user_status', $statusId);
        switch($statusId){
            case 2:
                $class = 'orange';
            break;
        }
        $tag = '<span class="sun-badge '.$class.'">'.$statusName.'</span>';
        
        return $tag;
    }
}
