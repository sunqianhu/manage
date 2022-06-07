<?php
/**
 * 添加保存
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\RoleModel;
use library\model\system\RoleMenuModel;
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
$roleMenuModel = new RoleMenuModel();
$roleId = 0; // 角色id
$menuIds = array();

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
    'name' => 'require|max_length:64',
    'remark' => 'max_length:255',
    'menu_ids' => 'require|number_string:,'
);
$validateService->message = array(
    'name.require' => '请输入角色名称',
    'name.max_length' => '角色名称不能大于64个字',
    'remark.max_length' => '角色名称不能大于255个字',
    'menu_ids.require' => '请选择菜单权限',
    'menu_ids.number_string' => '菜单权限参数错误'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}
$menuIds = explode(',', $_POST['menu_ids']);

// 入库
$data = array(
    'name'=>$_POST['name'],
    'remark'=>$_POST['remark'],
    'time_add'=>time(),
    'time_edit'=>time()
);
try{
    $roleId = $roleModel->insert($data);
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

// 关联
$roleMenuModel->delete(array(
    'mark'=>'role_id = :role_id',
    'value'=>array(
        ':role_id'=>$roleId
    )
));
foreach($menuIds as $menuId){
    $data = array(
        'role_id'=>$roleId,
        'menu_id'=>$menuId
    );
    $roleMenuModel->insert($data);
}

$return['status'] = 'success';
$return['message'] = '添加成功';
echo json_encode($return);