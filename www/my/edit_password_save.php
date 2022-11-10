<?php
/**
 * 自己修改密码保存
 */
require_once '../main.php';

use library\helper\Auth;
use library\core\Db;
use library\core\Validate;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$user = array();
$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据

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
$pdoStatement = $db->query($pdo, $sql, $data);
$user = $db->fetch($pdoStatement);
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
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>