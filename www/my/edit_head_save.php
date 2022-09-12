<?php
/**
 * 修改头像保存
 */
require_once '../library/app.php';

use \library\Session;
use \library\Db;
use \library\UserFileUpload;
use \library\User;
use \library\Auth;

Session::start();

$return = array(
    'status'=>'error',
    'msg'=>''
); // 返回数据
$user = array();
$path = ''; // 头像路径
$sql = '';
$data = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
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

// 上传文件
if(!UserFileUpload::upload('user_head', 'head')){
    $return['message'] = UserFileUpload::getError();
    echo json_encode($return);
    exit;
}
$path = UserFileUpload::$path;

// 更新
$sql = 'update user set head = :head where id = :id';
$data = array(
    ':head'=>$path,
    ':id'=>$user['id']
);
Db::update($sql, $data);

$_SESSION['user']['head'] = $path;
$_SESSION['user']['head_url'] = User::getHeadUrl($path);

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>