<?php
/**
 * 删除
 */
require_once '../../library/app.php';

use library\model\RoleModel;
use library\Db;
use library\Validate;
use library\Auth;

$return = array(
    'status'=>'error',
    'message'=>''
);
$roleModel = new RoleModel();
$rolePermissionModel = new RolePermissionModel();
$role = array();

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
    'id' => 'require:number'
);
Validate::setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
);
if(!Validate::check($_GET)){
    $return['message'] = Validate::getErrorMessage();
    echo json_encode($return);
    exit;
}

$role = Db::selectRow('id', array(
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
    Db::delete(
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

Db::delete(
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