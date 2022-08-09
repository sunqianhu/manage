<?php
/**
 * 操作日志服务
 */
namespace library\service;

use \library\model\OperationLogModel;
use \library\service\IpService;

class OperationLogService{
    
    /**
     * 添加操作日志
     * @access public
     * @param int $id 用户id
     * @return string 用户姓名
     */
    static function add(){
        $operationLogModel = new OperationLogModel();
        $departmentId = 0;
        $userId = 0;
        $data = array();
        $ip = IpService::getIp();
        $request = '';
        $url = '';
        $userAgent = '';
        
        if(!empty($_SESSION['police']['department']['id'])){
            $departmentId = $_SESSION['police']['department']['id'];
        }
        if(!empty($_SESSION['police']['user']['id'])){
            $userId = $_SESSION['police']['user']['id'];
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
        $data = array(
            'department_id'=>$departmentId,
            'user_id'=>$userId,
            'url'=>$url,
            'ip'=>$ip,
            'user_agent'=>$userAgent,
            'request'=>$request,
            'time_add'=>time()
        );
        
        return $operationLogModel->insert($data);
    }
    
}
