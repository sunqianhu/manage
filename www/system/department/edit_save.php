<?php
/**
 * 修改保存
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Db;
use library\core\Validate;

$validate = new Validate();
$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$departmentCurrent = array(); // 本部门
$departmentParent = array(); // 上级部门
$sql = '';
$data = array();
$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据

// 验证
if(!Auth::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!Auth::isPermission('system_department')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validate->setRule(array(
    'id' => 'require|number',
    'parent_id' => 'number',
    'name' => 'require|max_length:25',
    'sort' => 'number|max_length:10'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'parent_id.number' => '请选择上级部门',
    'name.require' => '请输入部门名称',
    'name.max_length' => '部门名称不能大于32个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
));
if(!$validate->check($_POST)){
    $return['message'] = $validate->getErrorMessage();
    $return['data']['dom'] = '#'.$validate->getErrorField();
    echo json_encode($return);
    exit;
}
if($_POST['id'] == '1'){
    $return['message'] = '不能修改根部门';
    echo json_encode($return);
    exit;
}

// 本部门
$sql = 'select id, parent_id from department where id = :id';
$data = array(
    ':id'=>$_POST['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$departmentCurrent = $db->fetch($pdoStatement);
if(empty($departmentCurrent)){
    $return['message'] = '此部门没有找到';
    echo json_encode($return);
    exit;
}

// 上级部门
$sql = 'select parent_ids from department where id = :id';
$data = array(
    ':id'=>$_POST['parent_id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$departmentParent = $db->fetch($pdoStatement);

// 更新
$sql = 'update department set
    parent_id = :parent_id,
    parent_ids = :parent_ids,
    name = :name,
    sort = :sort,
    remark = :remark
where id = :id';
$data = array(
    ':parent_id'=>$_POST['parent_id'],
    ':parent_ids'=>$departmentParent['parent_ids'].','.$departmentCurrent['id'],
    ':name'=>$_POST['name'],
    ':sort'=>$_POST['sort'],
    ':remark'=>$_POST['remark'],
    ':id'=>$departmentCurrent['id']
);
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>