<?php
/**
 * 操作日志服务
 */
namespace library\service\system;

use \library\model\system\OperationLogModel;
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
        
        if(!empty($_SESSION['department']['id'])){
            $departmentId = $_SESSION['department']['id'];
        }
        if(!empty($_SESSION['user']['id'])){
            $userId = $_SESSION['user']['id'];
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
            'url'=>$_SERVER['REQUEST_URI'],
            'ip'=>$ip,
            'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
            'request'=>$request,
            'time_add'=>time()
        );
        
        return $operationLogModel->insert($data);
    }
    
}
