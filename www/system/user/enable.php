<?php
/**
 * 启用
 */
require_once '../../main.php';

use library\core\Db;
use library\core\Validate;
use library\helper\Auth;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$user = array();
$return = array(
    'status'=>'error',
    'message'=>''
);

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_user')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}
$validate->setRule(array(
    'id' => 'require:number'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
));
if(!$validate->check($_GET)){
    $return['message'] = $validate->getErrorMessage();
    echo json_encode($return);
    exit;
}

// 本用户
$sql = 'select id, status_id from user where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$user = $db->fetch($pdoStatement);
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}
if($user['status_id'] == 1){
    $return['message'] = '用户已经是启用状态';
    echo json_encode($return);
    exit;
}

$sql = 'update user set
status_id = :status_id
where id = :id';
$data = array(
    ':status_id'=>1,
    ':id'=>$user['id']
);
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '启用成功';
echo json_encode($return);
?>