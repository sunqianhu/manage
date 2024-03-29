<?php
/**
 * 修改头像保存
 */
require_once '../main.php';

use library\helper\Auth;
use library\core\Db;
use library\helper\User;
use library\core\UserFileUpload;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$user = array();
$path = ''; // 头像路径
$userHelper = new User();
$userFileUpload = new UserFileUpload();
$return = array(
    'status'=>'error',
    'msg'=>''
); // 返回数据

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
$pdoStatement = $db->query($pdo, $sql, $data);
$user = $db->fetch($pdoStatement);
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}

// 上传文件
if(!$userFileUpload->upload('user_head', 'head')){
    $return['message'] = $userFileUpload->getError();
    echo json_encode($return);
    exit;
}
$path = $userFileUpload->path;

// 更新
$sql = 'update user set head = :head where id = :id';
$data = array(
    ':head'=>$path,
    ':id'=>$user['id']
);
$db->query($pdo, $sql, $data);

$_SESSION['user']['head'] = $path;
$_SESSION['user']['head_url'] = $userHelper->getHeadUrl($path);

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>