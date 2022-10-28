<?php
/**
 * 删除
 */
require_once '../../library/app.php';

use library\Auth;
use library\DbHelper;
use library\Validate;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$role = array();
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
if(!Auth::isPermission('system_role')){
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

$sql = 'select id from role where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$role = $dbHelper->fetch($pdoStatement);
if(empty($role)){
    $return['message'] = '角色没有找到';
    echo json_encode($return);
    exit;
}

$sql = 'delete from role where id = :id';
$data = array(
    ':id'=>$role['id']
);
if(!$dbHelper->query($pdo, $sql, $data)){
    $return['message'] = $dbHelper->getError();
    echo json_encode($return);
    exit;
}

$sql = 'delete from role_permission where role_id = :role_id';
$data = array(
    ':role_id'=>$role['id']
);
$dbHelper->query($pdo, $sql, $data);

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>