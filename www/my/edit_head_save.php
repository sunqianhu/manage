<?php
/**
 * 修改头像保存
 */
require_once '../library/app.php';

use library\Db;
use library\UserFileUpload;
use library\User;
use library\Auth;

$return = array(
    'status'=>'error',
    'msg'=>''
); // 返回数据
$userModel = new UserModel();
$user = array();
$path = ''; // 头像路径
$data = array();
$returnUpload = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}

// 本用户
$user = Db::selectRow('id', array(
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
try{
    $returnUpload = UserFileUpload::upload('user_head', 'head');
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}
$path = $returnUpload['path'];

// 更新
$data = array(
    'head'=>$path
);
Db::update($data, array(
    'mark'=>'id = :id',
    'value'=> array(
        ':id'=>$user['id']
    )
));

$_SESSION['user']['head'] = $path;
$_SESSION['user']['head_url'] = User::getHeadUrl($path);

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>