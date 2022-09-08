<?php
/**
 * 操作日志服务
 */
namespace library;

use \library\Db;
use \library\Ip;

class OperationLog{
    
    /**
     * 添加操作日志
     * @access public
     * @param int $id 用户id
     * @return string 用户姓名
     */
    static function add(){
        $departmentId = 0;
        $userId = 0;
        $data = array();
        $ip = Ip::get();
        $request = '';
        $url = '';
        $userAgent = '';
        $sql = '';
        
        if(!empty($_SESSION['department']['id'])){
            $departmentId = $_SESSION['department']['id'];
        }
        if(!empty($_SESSION['user']['id'])){
            $userId = $_SESSION['user']['id'];
        }
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
        
        $sql = 'insert into operation_log(user_id,department_id,url,ip,user_agent,request,time_add) values(:user_id,:department_id,:url,:ip,:user_agent,:request,:time_add)';
        $data = array(
            ':department_id'=>$departmentId,
            ':user_id'=>$userId,
            ':url'=>$url,
            ':ip'=>$ip,
            ':user_agent'=>$userAgent,
            ':request'=>$request,
            ':time_add'=>time()
        );
        return Db::insert($sql, $data);
    }
    
}
