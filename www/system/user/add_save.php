<?php
/**
 * 添加保存
 */
require_once '../../library/app.php';

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
$sql = '';
$data = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_user')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}
Validate::setRule(array(
    'username' => 'require|max_length:64',
    'status' => 'require|number',
    'password' => 'require|min_length:8',
    'name' => 'require|max_length:32',
    'phone' => 'require|number|min_length:11|max_length:11',
    'department_id' => 'require:^0|number',
    'role_ids' => 'require|number_array'
));
Validate::setMessage(array(
    'username.require' => '请输入用户名',
    'username.max_length' => '用户名不能大于64个字',
    'password.require' => '请输入密码',
    'password.min_length' => '密码不能小于8个字符',
    'name.require' => '请输入姓名',
    'name.max_length' => '姓名不能大于32个字',
    'phone.require' => '请输入手机号码',
    'phone.number' => '手机号码只能是数字',
    'phone.max_length' => '手机号码只能11位',
    'phone.min_length' => '手机号码只能11位',
    'department_id.require' => '请选择部门',
    'department_id.number' => '部门参数必须是个数字',
    'role_ids.require' => '请选择角色',
    'role_ids.number_array' => '角色参数错误'
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

$_POST['role_id_string'] = implode(',', $_POST['role_ids']);

$sql = 'select id from user where username = :username';
$data = array(
    ':username'=>$_POST['username']
);
$user = Db::selectRow($sql, $data);
if(!empty($user)){
    $return['message'] = '用户名已经存在';
    $return['data']['dom'] = '#username';
    echo json_encode($return);
    exit;
}

$sql = 'select id from user where phone = :phone';
$data = array(
    ':phone'=>$_POST['phone']
);
$user = Db::selectRow($sql, $data);
if(!empty($user)){
    $return['message'] = '手机号码已经存在';
    $return['data']['dom'] = '#phone';
    echo json_encode($return);
    exit;
}

// 入库
$sql = 'insert into user(username,status,password,name,phone,department_id,role_id_string,time_add) values(:username,:status,:password,:name,:phone,:department_id,:role_id_string,:time_add)';
$data = array(
    ':username'=>$_POST['username'],
    ':status'=>$_POST['status'],
    ':password'=>md5($_POST['password']),
    ':name'=>$_POST['name'],
    ':phone'=>$_POST['phone'],
    ':department_id'=>$_POST['department_id'],
    ':role_id_string'=>$_POST['role_id_string'],
    ':time_add'=>time()
);
if(!Db::insert($sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '添加成功';
echo json_encode($return);
?>