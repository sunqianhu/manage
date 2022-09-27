<?php
/**
 * 删除
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
$role = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_role')){
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

$sql = 'select id from role where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$role = Db::fetch($pdoStatement);
if(empty($role)){
    $return['message'] = '角色没有找到';
    echo json_encode($return);
    exit;
}

$sql = 'delete from role where id = :id';
$data = array(
    ':id'=>$role['id']
);
if(!Db::query($pdo, $sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$sql = 'delete from role_permission where role_id = :role_id';
$data = array(
    ':role_id'=>$role['id']
);
Db::query($pdo, $sql, $data);

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>