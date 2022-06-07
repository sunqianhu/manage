<?php
/**
 * 删除
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\MenuModel;
use library\service\ValidateService;
use library\service\AuthService;

$return = array(
    'status'=>'error',
    'message'=>''
);
$menuChild = array();
$menuModel = new MenuModel();
$validateService = new ValidateService();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_menu')){
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
    $return['message'] = '不能删除根菜单';
    echo json_encode($return);
    exit;
}

$menuChild = $menuModel->selectRow(
    'id',
    array(
        'mark'=>'parent_id = :id',
        'value'=> array(
            ':id'=>$_GET['id']
        )
    )
);
if(!empty($menuChild)){
    $return['message'] = '该菜单存在下级菜单';
    echo json_encode($return);
    exit;
}

try{
    $menuModel->delete(
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