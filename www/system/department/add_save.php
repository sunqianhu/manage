<?php
/**
 * 添加保存
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Db;
use library\core\Validate;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$departmentParent = array(); // 上级部门
$id = 0; // 添加部门id
$parentIds = ''; // 所有上级部门id
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
    'parent_id' => 'number',
    'name' => 'require|max_length:25',
    'sort' => 'number|max_length:10'
));
$validate->setMessage(array(
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

// 上级部门
$sql = 'select parent_ids from department where id = :id';
$data = array(
    ':id'=>$_POST['parent_id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$departmentParent = $db->fetch($pdoStatement);

// 入库
$sql = "insert into department(parent_id, name, sort, remark) values(:parent_id, :name, :sort, :remark)";
$data = array(
    ':parent_id'=>$_POST['parent_id'],
    ':name'=>$_POST['name'],
    ':sort'=>$_POST['sort'],
    ':remark'=>$_POST['remark']
);
if(!$db->query($pdo, $sql, $data)){
    $return['message'] = $db->getError();
    echo json_encode($return);
    exit;
}
$id = $pdo->lastInsertId();

// 父级id
$parentIds = $departmentParent['parent_ids'].','.$id;
$sql = 'update department set parent_ids = :parent_ids where id = :id';
$data = array(
    ':parent_ids'=>$parentIds,
    ':id'=>$id
);
$db->query($pdo, $sql, $data);

$return['status'] = 'success';
$return['message'] = '添加成功';
echo json_encode($return);
?>