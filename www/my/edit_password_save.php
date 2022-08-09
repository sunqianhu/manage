<?php
/**
 * 自己修改密码保存
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\model\UserModel;
use library\service\ValidateService;
use library\service\AuthService;

$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$validateService = new ValidateService();
$userModel = new UserModel();
$user = array();
$data = array();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
$validateService->rule = array(
    'password' => 'require|min_length:8',
    'password2' => 'require|min_length:8',
);
$validateService->message = array(
    'password.require' => '请输入新密码',
    'password.min_length' => '新密码不能小于8个字符',
    'password2.require' => '请输入确认新密码',
    'password2.min_length' => '确认新密码不能小于8个字符',
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
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

// 更新
$data = array(
    'password'=>md5($_POST['password'])
);
$userModel->update($data, array(
    'mark'=>'id = :id',
    'value'=> array(
        ':id'=>$user['id']
    )
));

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>