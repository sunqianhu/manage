<?php
/**
 * 修改保存
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use \library\model\system\DepartmentModel;
use \library\service\ValidateService;

$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$validateService = new ValidateService();
$departmentModel = new DepartmentModel();
$departmentCurrent = array(); // 本部门
$departmentParent = array(); // 上级部门
$data = array();


// 验证
$validateService->rule = array(
    'id' => 'require|number',
    'parent_id' => 'number',
    'name' => 'require|max_length:25',
    'sort' => 'number|max_length:10'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
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
if($_POST['id'] == '1'){
    $return['message'] = '不能修改根部门';
    echo json_encode($return);
    exit;
}

// 本部门
$departmentCurrent = $departmentModel->selectRow(
    'id, parent_id',
    array(
        'mark'=> 'id = :id',
        'value'=> array(
            ':id'=>$_POST['id']
        )
    )
);
if(empty($departmentCurrent)){
    $return['message'] = '此部门没有找到';
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

// 更新
$data = array(
    'parent_id'=>$_POST['parent_id'],
    'parent_ids'=>$departmentParent['parent_ids'].','.$departmentCurrent['id'],
    'name'=>$_POST['name'],
    'sort'=>$_POST['sort'],
    'remark'=>$_POST['remark']
);
try{
    $id = $departmentModel->update($data, array(
        'mark'=>'id = :id',
        'value'=> array(
            ':id'=>$departmentCurrent['id']
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