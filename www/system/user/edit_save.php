<?php
/**
 * 修改保存
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\UserModel;
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
$userModel = new UserModel();
$user = array();
$data = array();

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
    'id' => 'require|number',
    'status' => 'require|number',
    'name' => 'require|max_length:32',
    'phone' => 'require|number|min_length:11|max_length:11',
    'department_id' => 'require|number',
    'role_ids' => 'require|number_array'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'name.require' => '请输入姓名',
    'name.max_length' => '姓名不能大于32个字',
    'phone.require' => '请输入手机号码',
    'phone.number' => '手机号码只能是数字',
    'phone.max_length' => '手机号码只能11位',
    'phone.min_length' => '手机号码只能11位',
    'department_id.require' => '请选择部门',
    'department_id.number' => '部门参数必须是个数字',
    'role_ids.require' => '请选择角色',
    'role_ids.number_array' => '角色参数错误'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}
$_POST['role_id_string'] = implode(',', $_POST['role_ids']);

// 本用户
$user = $userModel->selectRow('id', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_POST['id']
    )
));
if(empty($user)){
    $return['message'] = '用户没有找到';
    echo json_encode($return);
    exit;
}

// 更新
$data = array(
    'status'=>$_POST['status'],
    'name'=>$_POST['name'],
    'phone'=>$_POST['phone'],
    'department_id'=>$_POST['department_id'],
    'role_id_string'=>$_POST['role_id_string'],
    'time_edit'=>time()
);
if($_POST['password'] !== ''){
    $data['password'] = md5($_POST['password']);
}
try{
    $userModel->update($data, array(
        'mark'=>'id = :id',
        'value'=> array(
            ':id'=>$user['id']
        )
    ));
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>