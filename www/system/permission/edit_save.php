<?php
/**
 * 修改保存
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
$permissionCurrent = array(); // 本权限
$permissionParent = array(); // 上级权限
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
if(!Auth::isPermission('system_permission')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validate->setRule(array(
    'id' => 'require|number',
    'parent_id' => 'number',
    'name' => 'require|max_length:32',
    'tag' => 'require|max_length:64',
    'sort' => 'number|max_length:10'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'parent_id.number' => '请选择上级权限',
    'name.require' => '请输入权限名称',
    'name.max_length' => '权限名称不能大于32个字',
    'tag.require' => '请输入权限标识',
    'tag.max_length' => '权限标识不能大于64个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
));
if(!$validate->check($_POST)){
    $return['message'] = $validate->getErrorMessage();
    $return['data']['dom'] = '#'.$validate->getErrorField();
    echo json_encode($return);
    exit;
}

// 本权限
$sql = 'select id, parent_id from permission where id = :id';
$data = array(
    ':id'=>$_POST['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$permissionCurrent = $db->fetch($pdoStatement);
if(empty($permissionCurrent)){
    $return['message'] = '此权限没有找到';
    echo json_encode($return);
    exit;
}

// 上级权限
$sql = 'select parent_ids from permission where id = :id';
$data = array(
    ':id'=>$_POST['parent_id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$permissionParent = $db->fetch($pdoStatement);

// 更新
$sql = 'update permission set
parent_id = :parent_id,
parent_ids = :parent_ids,
name = :name,
tag = :tag,
sort = :sort
where id = :id';
$data = array(
    ':parent_id'=>$_POST['parent_id'],
    ':parent_ids'=>$permissionParent['parent_ids'].','.$permissionCurrent['id'],
    ':name'=>$_POST['name'],
    ':tag'=>$_POST['tag'],
    ':sort'=>$_POST['sort'],
    ':id'=>$permissionCurrent['id']
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