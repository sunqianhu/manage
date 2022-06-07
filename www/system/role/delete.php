<?php
/**
 * 删除
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\RoleModel;
use library\model\system\RoleMenuModel;
use library\service\ValidateService;
use library\service\AuthService;

$return = array(
    'status'=>'error',
    'message'=>''
);
$roleModel = new RoleModel();
$roleMenuModel = new RoleMenuModel();
$validateService = new ValidateService();
$role = array();

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
    'id' => 'require:number'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
);
if(!$validateService->check($_GET)){
    $return['message'] = $validateService->getErrorMessage();
    echo json_encode($return);
    exit;
}

$role = $roleModel->selectRow('id', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
if(empty($role)){
    $return['message'] = '角色没有找到';
    echo json_encode($return);
    exit;
}

try{
    $roleModel->delete(
        array(
            'mark'=>'id = :id',
            'value'=> array(
                ':id'=>$role['id']
            )
        )
    );
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$roleMenuModel->delete(
    array(
        'mark'=>'role_id = :role_id',
        'value'=> array(
            ':role_id'=>$role['id']
        )
    )
);

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>