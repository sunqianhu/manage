<?php
/**
 * 删除
 */
require_once '../../library/app.php';

use \library\Db;
use \library\Validate;
use \library\Auth;

$return = array(
    'status'=>'error',
    'message'=>''
);
$departmentChild = array();
$sql = '';
$data = array();

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
if($_GET['id'] == '1'){
    $return['message'] = '不能删除根部门';
    echo json_encode($return);
    exit;
}

$sql = 'select id from department where parent_id = :id limit 0,1';
$data = array(
    ':id'=>$_GET['id']
);
$departmentChild = Db::selectRow($sql, $data);
if(!empty($departmentChild)){
    $return['message'] = '该部门存在下级部门';
    echo json_encode($return);
    exit;
}

$sql = 'delete from department where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
if(!Db::delete($sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>