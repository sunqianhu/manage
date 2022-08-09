<?php
/**
 * 修改保存
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\RoleModel;
use library\model\RolePermissionModel;
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
$roleModel = new RoleModel();
$rolePermissionModel = new RolePermissionModel();
$role = array();
$data = array();
$permissionIds = array();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_role')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validateService->rule = array(
    'id' => 'require|number',
    'name' => 'require|max_length:64',
    'remark' => 'max_length:255',
    'permission_ids' => 'require|number_string:,'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'name.require' => '请输入角色名称',
    'name.max_length' => '角色名称不能大于64个字',
    'remark.max_length' => '角色名称不能大于255个字',
    'permission_ids.require' => '请选择权限',
    'permission_ids.number_string' => '权限参数错误'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}
$permissionIds = explode(',', $_POST['permission_ids']);

// 本角色
$role = $roleModel->selectRow(
    'id',
    array(
        'mark'=> 'id = :id',
        'value'=> array(
            ':id'=>$_POST['id']
        )
    )
);
if(empty($role)){
    $return['message'] = '角色没有找到';
    echo json_encode($return);
    exit;
}

// 更新
$data = array(
    'name'=>$_POST['name'],
    'remark'=>$_POST['remark'],
    'time_edit'=>time()
);
try{
    $roleModel->update($data, array(
        'mark'=>'id = :id',
        'value'=> array(
            ':id'=>$role['id']
        )
    ));
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

// 关联
$rolePermissionModel->delete(array(
    'mark'=>'role_id = :role_id',
    'value'=>array(
        ':role_id'=>$role['id']
    )
));
foreach($permissionIds as $permissionId){
    $data = array(
        'role_id'=>$role['id'],
        'permission_id'=>$permissionId
    );
    $rolePermissionModel->insert($data);
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);




?>