<?php
/**
 * 登录
 */
require_once '../vendor/autoload.php';

use model\User;

$return = array(
    'status'=>'error',
    'msg'=>'',
    'dom'=>'',
    'captcha'=>'0'
);
$userModel = null; // 用户模型
$user = array();

if(empty($_POST['userid'])){
    $return['msg'] = '请输入用户名';
    $return['dom'] = '#userid';
    echo json_encode($return);
    exit;
}
if(empty($_POST['password'])){
    $return['msg'] = '请输入密码';
    $return['dom'] = '#password';
    echo json_encode($return);
    exit;
}
if(empty($_POST['captcha'])){
    $return['msg'] = '请输入验证码';
    $return['dom'] = '#captcha';
    echo json_encode($return);
    exit;
}
if(empty($_SESSION['captcha_login'])){
    $return['msg'] = '请重新获取验证码';
    $return['dom'] = '#captcha';
    $return['captcha'] = '1';
    echo json_encode($return);
    exit;
}
if($_SESSION['captcha_login'] != $_POST['captcha']){
    $return['msg'] = '验证码错误';
    $return['dom'] = '#captcha';
    $return['captcha'] = '1';
    echo json_encode($return);
    exit;
}

$userModel = new User();
try{
    $user = $userModel->getRowByUseridPassword($_POST['userid'], $_POST['password']);
}catch(Exception $e){
    $return['msg'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

// 服务层
Auth::saveSessionUser($user);

$return['status'] = 'success';
$return['msg'] = '登录成功';
echo json_encode($return);
?>