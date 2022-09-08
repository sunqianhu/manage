<?php
/**
 * 登录
 */
require_once '../library/app.php';

use \library\Db;
use \library\Config;
use \library\Validate;
use \library\Auth;
use \library\Ip;
use \library\User;
use \library\Dictionary;

$sql = '';
$data = array(); // 数据
$user = array();
$department = array();
$permissions = array(); // 权限
$ip = '';
$return = array(
    'status'=>'error',
    'message'=>'',
    'data'=>array(
        'dom'=>'',
        'captcha'=>'0'
    )
);

// 验证
Validate::setRule(array(
    'username' => 'require|max_length:64',
    'password' => 'require',
    'captcha' => 'require|max_length:6'
));
Validate::setMessage(array(
    'username.require' => '请输入用户名',
    'username.max_length' => '用户名不能超过64个字',
    'password.require' => '请输入密码',
    'captcha.require' => '请输入验证码',
    'captcha.max_length' => '验证码长度不能大于6个字符'
));
if(!Validate::check($_POST)){
    $return['message'] = Validate::getErrorMessage();
    $return['data']['dom'] = Validate::getErrorField();
    echo json_encode($return);
    exit;
}

// 验证码
if(empty($_SESSION['login_captcha'])){
    $return['message'] = '请重新获取验证码';
    $return['data']['dom'] = '#captcha';
    $return['data']['captcha'] = '1';
    echo json_encode($return);
    exit;
}
if($_SESSION['login_captcha'] != $_POST['captcha']){
    $return['message'] = '验证码错误';
    $return['data']['dom'] = '#captcha';
    $return['data']['captcha'] = '1';
    echo json_encode($return);
    exit;
}
unset($_SESSION['login_captcha']);
$return['data']['captcha'] = '1';

// 用户
$sql = 'select id, username, name, department_id, role_id_string, head, status, time_login, ip from user where username = :username and password = :password limit 0,1';
$data = array(
    ':username'=>$_POST['username'],
    ':password'=>md5($_POST['password'])
);
$user = Db::selectRow($sql, $data);
if(empty($user)){
    $return['message'] = '用户名或密码错误';
    echo json_encode($return);
    exit;
}
if($user['status'] != 1){
    $return['message'] = Dictionary::getValue('system_user_status', $user['status']);
    echo json_encode($return);
    exit;
}
$user['head_url'] = User::getHeadUrl($user['head']);

// 部门
$sql = 'select id, name from department where id = :id';
$data = array(
    ':id'=>$user['department_id']
);
$department = Db::selectRow($sql, $data);
if(empty($department)){
    $return['message'] = '用户还没有设置部门';
    echo json_encode($return);
    exit;
}

// 权限
$sql = 'select id, parent_id, type, name, tag from permission where id in (select permission_id from role_permission where role_id in (:role_id))';
$data = array(
    ':role_id'=> $user['role_id_string']
);
$permissions = Db::selectAll($sql, $data);

// 记录
$ip = Ip::get();
$sql = "update user set time_login = ".time().", ip = '".$ip."' where id = :id";
$data = array(
    ':id'=>$user['id']
);
Db::update($sql, $data);

// 日志
$sql = 'insert into login_log(user_id, department_id, time_login, ip) values(:user_id, :department_id, :time_login, :ip)';
$data = array(
    ':user_id'=>$user['id'],
    ':department_id'=>$department['id'],
    ':time_login'=>time(),
    ':ip'=>$ip
);
Db::insert($sql, $data);

// 会话
$_SESSION['user'] = $user;
$_SESSION['department'] = $department;
$_SESSION['permission'] = $permissions;

$return['status'] = 'success';
$return['message'] = '登录成功';
echo json_encode($return);
?>