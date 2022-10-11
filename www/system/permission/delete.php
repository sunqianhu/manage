<?php
/**
 * 删除
 */
require_once '../../library/app.php';

use library\Auth;
use library\DbHelper;
use library\Validate;

$validate = new Validate();
$dbHelper = new DbHelper();
$pdo = $dbHelper->getInstance();
$pdoStatement = null;
$return = array(
    'status'=>'error',
    'message'=>''
);
$permissionChild = array();
$sql = '';
$data = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_permission')){
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
if($_GET['id'] == '1'){
    $return['message'] = '不能删除根权限';
    echo json_encode($return);
    exit;
}

$sql = 'select id from permission where parent_id = :parent_id limit 0,1';
$data = array(
    ':parent_id'=>$_GET['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$permissionChild = $dbHelper->fetch($pdoStatement);
if(!empty($permissionChild)){
    $return['message'] = '该权限存在下级权限';
    echo json_encode($return);
    exit;
}

$sql = 'delete from permission where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
if(!$dbHelper->query($pdo, $sql, $data)){
    $return['message'] = $dbHelper->getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>