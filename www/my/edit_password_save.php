<?php
/**
 * 自己修改密码保存
 */
require_once '../library/app.php';

use library\Session;
use library\Db;
use library\Validate;
use library\Auth;

$pdo = Db::getInstance();
$pdoStatement = null;
$sql = '';
$validate = new Validate();
$data = array();
$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$user = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
$validate->setRule(array(
    'password' => 'require|min_length:8',
    'password2' => 'require|min_length:8',
));
$validate->setMessage(array(
    'password.require' => '请输入新密码',
    'password.min_length' => '新密码不能小于8个字符',
    'password2.require' => '请输入确认新密码',
    'password2.min_length' => '确认新密码不能小于8个字符',
));
if(!$validate->check($_POST)){
    $return['message'] = $validate->getErrorMessage();
    $return['data']['dom'] = '#'.$validate->getErrorField();
    echo json_encode($return);
    exit;
}
if($_POST['password'] != $_POST['password2']){
    $return['message'] = '两次输入密码不相同';
    $return['data']['dom'] = '#pasword';
    echo json_encode($return);
    exit;
}

// 本用户
$sql = "select id from user where id = :id";
$data = array(
    ':id'=>$_SESSION['user']['id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$user = Db::fetch($pdoStatement);
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}

// 更新
$sql = "update user set password = :password where id = :id";
$data = array(
    ':password'=>md5($_POST['password']),
    ':id'=>$user['id']
);
if(!Db::query($pdo, $sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>