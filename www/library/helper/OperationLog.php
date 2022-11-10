<?php
/**
 * 操作日志模型
 */
namespace library\helper;

use library\core\Db;
use library\core\Ip;

class OperationLog{
    
    /**
     * 添加操作日志
     * @access public
     * @return id 日志id
     */
    function add(){
        $db = new Db();
        $pdo = $db->getPdo();
        $pdoStatement = null;
        $sql = '';
        $data = array();
        $departmentId = 0;
        $userId = 0;
        $ip = new Ip();
        $ipString = $ip->get();
        $request = '';
        $url = '';
        $userAgent = '';
        $id = 0;
        
        if(empty($_SESSION['user']) || empty($_SESSION['department'])){
            return $id;
        }
        
        $departmentId = $_SESSION['department']['id'];
        $userId = $_SESSION['user']['id'];
        if(!empty($_SERVER['REQUEST_URI'])){
            $url = $_SERVER['REQUEST_URI'];
        }
        if(!empty($_SERVER['HTTP_USER_AGENT'])){
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if(!empty($_GET)){
            $request .= 'get参数：'.print_r($_GET, true);
        }
        if(!empty($_POST)){
            $request .= 'post参数：'.print_r($_POST, true);
        }
        
        $sql = 'insert into operation_log(user_id,department_id,url,ip,user_agent,request,add_time) values(:user_id,:department_id,:url,:ip,:user_agent,:request,:add_time)';
        $data = array(
            ':department_id'=>$departmentId,
            ':user_id'=>$userId,
            ':url'=>$url,
            ':ip'=>$ipString,
            ':user_agent'=>$userAgent,
            ':request'=>$request,
            ':add_time'=>time()
        );
        $pdoStatement = $db->query($pdo, $sql, $data);
        $id = $pdo->lastInsertId();
        
        return $id;
    }
}
