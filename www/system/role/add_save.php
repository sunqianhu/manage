<?php
/**
 * 添加保存
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\RoleModel;
use library\Db;
use library\Validate;
use library\Auth;

$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$roleModel = new RoleModel();
$rolePermissionModel = new RolePermissionModel();
$roleId = 0; // 角色id
$permissionIds = array();

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

Validate::setRule(array(
    'name' => 'require|max_length:64',
    'remark' => 'max_length:255',
    'permission_ids' => 'require|number_string:,'
);
Validate::setMessage(array(
    'name.require' => '请输入角色名称',
    'name.max_length' => '角色名称不能大于64个字',
    'remark.max_length' => '角色名称不能大于255个字',
    'permission_ids.require' => '请选择权限',
    'permission_ids.number_string' => '权限参数错误'
);
if(!Validate::check($_POST)){
    $return['message'] = Validate::getErrorMessage();
    $return['data']['dom'] = '#'.Validate::getErrorField();
    echo json_encode($return);
    exit;
}
$permissionIds = explode(',', $_POST['permission_ids']);

// 入库
$data = array(
    'name'=>$_POST['name'],
    'remark'=>$_POST['remark'],
    'time_add'=>time(),
    'time_edit'=>time()
);
try{
    $roleId = Db::insert($data);
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

// 关联
Db::delete(array(
    'mark'=>'role_id = :role_id',
    'value'=>array(
        ':role_id'=>$roleId
    )
));
foreach($permissionIds as $permissionId){
    $data = array(
        'role_id'=>$roleId,
        'permission_id'=>$permissionId
    );
    Db::insert($data);
}

$return['status'] = 'success';
$return['message'] = '添加成功';
echo json_encode($return);