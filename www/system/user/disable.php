<?php
/**
 * 停用
 */
require_once '../../library/app.php';

use \library\Session;
use \library\Auth;
use \library\Db;
use \library\Validate;

Session::start();

$pdo = Db::getInstance();
$pdoStatement = null;
$sql = '';
$data = array();
$return = array(
    'status'=>'error',
    'message'=>''
);
$user = array();

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
Validate::setRule(array(
    'id' => 'require:number'
));
Validate::setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
));
if(!Validate::check($_GET)){
    $return['message'] = Validate::getErrorMessage();
    echo json_encode($return);
    exit;
}

// 本用户
$sql = 'select id, status_id from user where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$user = Db::fetch($pdoStatement);
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}
if($user['status_id'] == 2){
    $return['message'] = '用户已经是停用状态';
    echo json_encode($return);
    exit;
}

$sql = 'update user set
status_id = :status_id
where id = :id';
$data = array(
    ':status_id'=>2,
    ':id'=>$user['id']
);
if(!Db::query($pdo, $sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '停用成功';
echo json_encode($return);
?>