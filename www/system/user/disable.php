<?php
/**
 * 停用
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\UserModel;
use library\service\ValidateService;
use library\service\AuthService;

$return = array(
    'status'=>'error',
    'message'=>''
);
$userModel = new UserModel();
$validateService = new ValidateService();
$user = array();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_user')){
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

// 本用户
$user = $userModel->selectRow(
    'id,status',
    array(
        'mark'=> 'id = :id',
        'value'=> array(
            ':id'=>$_GET['id']
        )
    )
);
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}
if($user['status'] == 2){
    $return['message'] = '用户已经是停用状态';
    echo json_encode($return);
    exit;
}

try{
    $userModel->update(
        array(
            'status'=>2
        ),
        array(
            'mark'=>'id = :id',
            'value'=> array(
                ':id'=>$user['id']
            )
        )
    );
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '停用成功';
echo json_encode($return);
?>