<?php
/**
 * 添加保存
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\PermissionModel;
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
$permissionModel = new PermissionModel();
$permissionParent = array(); // 上级权限
$id = 0; // 添加权限id
$parentIds = ''; // 所有上级权限id
$data = array();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_permission')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validateService->rule = array(
    'parent_id' => 'number',
    'type' => 'require',
    'name' => 'require|max_length:32',
    'tag' => 'require|max_length:64',
    'sort' => 'number|max_length:10'
);
$validateService->message = array(
    'parent_id.number' => '请选择上级权限',
    'type.require' => '请选择权限类型',
    'name.require' => '请输入权限名称',
    'name.max_length' => '权限名称不能大于32个字',
    'tag.require' => '请输入权限标识',
    'tag.max_length' => '权限标识不能大于64个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}

// 上级权限
$permissionParent = $permissionModel->selectRow(
    'parent_ids',
    array(
        'mark'=> 'id = :id',
        'value'=> array(
            ':id'=>$_POST['parent_id']
        )
    )
);

// 入库
$data = array(
    'parent_id'=>$_POST['parent_id'],
    'type'=>$_POST['type'],
    'name'=>$_POST['name'],
    'tag'=>$_POST['tag'],
    'sort'=>$_POST['sort']
);
try{
    $id = $permissionModel->insert($data);
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$parentIds = $permissionParent['parent_ids'].','.$id;
$permissionModel->update(
    array('parent_ids'=>$parentIds),
    array(
        'mark'=>'id = :id',
        'value'=> array(
            ':id'=>$id
        )
    )
);

$return['status'] = 'success';
$return['message'] = '添加成功';
echo json_encode($return);
?>