<?php
/**
 * 删除
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\DepartmentModel;
use library\service\ValidateService;
use library\service\AuthService;

$return = array(
    'status'=>'error',
    'message'=>''
);
$departmentChild = array();
$departmentModel = new DepartmentModel();
$validateService = new ValidateService();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_department')){
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
if($_GET['id'] == '1'){
    $return['message'] = '不能删除根部门';
    echo json_encode($return);
    exit;
}

$departmentChild = $departmentModel->selectRow(
    'id',
    array(
        'mark'=>'parent_id = :id',
        'value'=> array(
            ':id'=>$_GET['id']
        )
    )
);
if(!empty($departmentChild)){
    $return['message'] = '该部门存在下级部门';
    echo json_encode($return);
    exit;
}

try{
    $departmentModel->delete(
        array(
            'mark'=>'id = :id',
            'value'=> array(
                ':id'=>$_GET['id']

            )
        )
    );
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '删除成功';
echo json_encode($return);
?>