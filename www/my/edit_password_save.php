<?php
/**
 * 自己修改密码保存
 */
require_once '../library/app.php';

use \library\Session;
use \library\Db;
use \library\Validate;
use \library\Auth;

Session::start();

$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$user = array();
$data = array();
$sql = '';

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
Validate::setRule(array(
    'password' => 'require|min_length:8',
    'password2' => 'require|min_length:8',
));
Validate::setMessage(array(
    'password.require' => '请输入新密码',
    'password.min_length' => '新密码不能小于8个字符',
    'password2.require' => '请输入确认新密码',
    'password2.min_length' => '确认新密码不能小于8个字符',
));
if(!Validate::check($_POST)){
    $return['message'] = Validate::getErrorMessage();
    $return['data']['dom'] = '#'.Validate::getErrorField();
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
$user = Db::selectRow($sql, $data);
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
Db::update($sql, $data);

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>