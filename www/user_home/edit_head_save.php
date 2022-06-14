<?php
/**
 * 修改头像保存
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\model\system\UserModel;
use library\service\UserFileUploadService;
use library\service\system\UserService;
use library\service\AuthService;

$return = array(
    'status'=>'error',
    'msg'=>''
); // 返回数据
$userModel = new UserModel();
$user = array();
$path = ''; // 头像路径
$data = array();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}

// 本用户
$user = $userModel->selectRow('id', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_SESSION['user']['id']
    )
));
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}

// 上传文件
if(!UserFileUploadService::file('user_head', 'head')){
    $return['message'] = UserFileUploadService::$message;
    echo json_encode($return);
    exit;
}
$path = UserFileUploadService::$path;

// 更新
$data = array(
    'head'=>$path
);
$userModel->update($data, array(
    'mark'=>'id = :id',
    'value'=> array(
        ':id'=>$user['id']
    )
));

$_SESSION['user']['head'] = $path;
$_SESSION['user']['head_url'] = UserService::getHeadUrl($path);

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>