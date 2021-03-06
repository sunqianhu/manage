<?php
/**
 * 添加保存
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\DepartmentModel;
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
$departmentModel = new DepartmentModel();
$departmentParent = array(); // 上级部门
$id = 0; // 添加部门id
$parentIds = ''; // 所有上级部门id
$data = array();

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
    'parent_id' => 'number',
    'name' => 'require|max_length:25',
    'sort' => 'number|max_length:10'
);
$validateService->message = array(
    'parent_id.number' => '请选择上级部门',
    'name.require' => '请输入部门名称',
    'name.max_length' => '部门名称不能大于32个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}

// 上级部门
$departmentParent = $departmentModel->selectRow(
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
    'name'=>$_POST['name'],
    'sort'=>$_POST['sort'],
    'remark'=>$_POST['remark']
);
try{
    $id = $departmentModel->insert($data);
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$parentIds = $departmentParent['parent_ids'].','.$id;
$departmentModel->update(
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