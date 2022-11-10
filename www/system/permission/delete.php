<?php
/**
 * 删除
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Db;
use library\core\Validate;

$validate = new Validate();
$db = new Db();
$pdo = $db->getPdo();
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
$pdoStatement = $db->query($pdo, $sql, $data);
$permissionChild = $db->fetch($pdoStatement);
if(!empty($permissionChild)){
    $return['message'] = '该权限存在下级权限';
    echo json_encode($return);
    exit;
}

$sql = 'delete from permission where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>