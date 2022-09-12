<?php
/**
 * 删除
 */
require_once '../../library/app.php';

use \library\Session;
use \library\Db;
use \library\Validate;
use \library\Auth;

Session::start();

$return = array(
    'status'=>'error',
    'message'=>''
);
$role = array();
$sql = '';
$data = array();

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
$role = Db::selectRow($sql, $data);
if(empty($role)){
    $return['message'] = '角色没有找到';
    echo json_encode($return);
    exit;
}

$sql = 'delete from role where id = :id';
$data = array(
    ':id'=>$role['id']
);
if(!Db::delete($sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}
if(!Db::delete($sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>