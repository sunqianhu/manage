<?php
/**
 * 修改保存
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Db;
use library\core\Validate;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$role = array();
$permissionIds = array();
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
if(!Auth::isPermission('system_role')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validate->setRule(array(
    'id' => 'require|number',
    'name' => 'require|max_length:64',
    'remark' => 'max_length:255',
    'permission_ids' => 'require|number_string:,'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'name.require' => '请输入角色名称',
    'name.max_length' => '角色名称不能大于64个字',
    'remark.max_length' => '角色名称不能大于255个字',
    'permission_ids.require' => '请选择权限',
    'permission_ids.number_string' => '权限参数错误'
));
if(!$validate->check($_POST)){
    $return['message'] = $validate->getErrorMessage();
    $return['data']['dom'] = '#'.$validate->getErrorField();
    echo json_encode($return);
    exit;
}
$permissionIds = explode(',', $_POST['permission_ids']);

// 本角色
$sql = 'select id from role where id = :id';
$data = array(
    ':id'=>$_POST['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$role = $db->fetch($pdoStatement);
if(empty($role)){
    $return['message'] = '角色没有找到';
    echo json_encode($return);
    exit;
}

// 更新
$sql = 'update role set
name = :name,
remark = :remark,
edit_time = :edit_time
where id = :id';
$data = array(
    ':name'=>$_POST['name'],
    ':remark'=>$_POST['remark'],
    ':edit_time'=>time(),
    ':id'=>$role['id']
);
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}

// 关联
$sql = 'delete from role_permission where role_id = :role_id';
$data = array(
    ':role_id'=>$role['id']
);
$db->query($pdo, $sql, $data);
foreach($permissionIds as $permissionId){
    $sql = 'insert into role_permission(role_id,permission_id) values(:role_id,:permission_id)';
    $data = array(
        ':role_id'=>$role['id'],
        ':permission_id'=>$permissionId
    );
    $db->query($pdo, $sql, $data);
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>