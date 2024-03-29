<?php
/**
 * 修改保存
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Db;
use library\core\Validate;

$validate = new Validate();
$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$user = array();
$sql = '';
$data = array();
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
if(!Auth::isPermission('system_user')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}
$validate->setRule(array(
    'id' => 'require|number',
    'status_id' => 'require|number',
    'name' => 'require|max_length:32',
    'phone' => 'require|number|min_length:11|max_length:11',
    'department_id' => 'require|number',
    'role_ids' => 'require|number_array'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'status_id.require' => '请选择状态',
    'status_id.number' => 'status_id必须是个数字',
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
if(!$validate->check($_POST)){
    $return['message'] = $validate->getErrorMessage();
    $return['data']['dom'] = '#'.$validate->getErrorField();
    echo json_encode($return);
    exit;
}
$_POST['role_id_string'] = implode(',', $_POST['role_ids']);

// 本用户
$sql = 'select id from user where id = :id';
$data = array(
    ':id'=>$_POST['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$user = $db->fetch($pdoStatement);
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}

// 更新
$sql = 'update user set
status_id = :status_id,
name = :name,
phone = :phone,
department_id = :department_id,
role_id_string = :role_id_string,
edit_time = :edit_time
[password]
where id = :id';
$data = array(
    ':status_id'=>$_POST['status_id'],
    ':name'=>$_POST['name'],
    ':phone'=>$_POST['phone'],
    ':department_id'=>$_POST['department_id'],
    ':role_id_string'=>$_POST['role_id_string'],
    ':edit_time'=>time(),
    ':id'=>$user['id']
);
if($_POST['password'] !== ''){
    $sql = str_replace('[password]', ',password = :password', $sql);
    $data[':password'] = md5($_POST['password']);
}else{
    $sql = str_replace('[password]', '', $sql);
}
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>