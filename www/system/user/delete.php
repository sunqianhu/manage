<?php
/**
 * 删除
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use \library\model\system\UserModel;
use \library\service\ValidateService;

$return = array(
    'status'=>'error',
    'message'=>''
);
$userModel = new UserModel();
$validateService = new ValidateService();
$user = array();

// 验证
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
    'id',
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

try{
    $userModel->delete(
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
$return['message'] = '删除成功';
echo json_encode($return);


?>