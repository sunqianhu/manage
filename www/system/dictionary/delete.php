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

$pdo = Db::getInstance();
$return = array(
    'status'=>'error',
    'message'=>''
);
$sql = '';
$data = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_dictionary')){
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

$sql = 'delete from dictionary where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
if(!Db::query($pdo, $sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);



?>