<?php
/**
 * 启用
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\Db;
use library\Validate;
use library\Auth;

$return = array(
    'status'=>'error',
    'message'=>''
);
$userModel = new UserModel();
$user = array();

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_user')){
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

// 本用户
$user = Db::selectRow(
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
if($user['status'] == 1){
    $return['message'] = '用户已经是启用状态';
    echo json_encode($return);
    exit;
}

try{
    Db::update(
        array(
            'status'=>1
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
$return['message'] = '启用成功';
echo json_encode($return);
?>