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
$departmentChild = array();
$sql = '';
$data = array();
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
if(!Auth::isPermission('system_department')){
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
    $return['message'] = '不能删除根部门';
    echo json_encode($return);
    exit;
}

$sql = 'select id from department where parent_id = :id limit 0,1';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$departmentChild = $db->fetch($pdoStatement);
if(!empty($departmentChild)){
    $return['message'] = '该部门存在下级部门';
    echo json_encode($return);
    exit;
}

$sql = 'delete from department where id = :id';
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