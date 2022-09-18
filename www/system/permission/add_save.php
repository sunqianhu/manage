<?php
/**
 * 添加保存
 */
require_once '../../library/app.php';

use \library\Session;
use \library\Db;
use \library\Validate;
use \library\Auth;

Session::start();

$pdo = Db::getInstance();
$pdoStatement = null;
$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$permissionParent = array(); // 上级权限
$id = 0; // 添加权限id
$parentIds = ''; // 所有上级权限id
$sql = '';
$data = array();

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

Validate::setRule(array(
    'parent_id' => 'number',
    'type' => 'require',
    'name' => 'require|max_length:32',
    'tag' => 'require|max_length:64',
    'sort' => 'number|max_length:10'
));
Validate::setMessage(array(
    'parent_id.number' => '请选择上级权限',
    'type.require' => '请选择权限类型',
    'name.require' => '请输入权限名称',
    'name.max_length' => '权限名称不能大于32个字',
    'tag.require' => '请输入权限标识',
    'tag.max_length' => '权限标识不能大于64个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
));
if(!Validate::check($_POST)){
    $return['message'] = Validate::getErrorMessage();
    $return['data']['dom'] = '#'.Validate::getErrorField();
    echo json_encode($return);
    exit;
}

// 上级权限
$sql = 'select parent_ids from permission where id = :id';
$data = array(
    ':id'=>$_POST['parent_id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$permissionParent = Db::fetch($pdoStatement);

// 入库
$sql = 'insert into permission(parent_id,type,name,tag,sort) values(:parent_id,:type,:name,:tag,:sort)';
$data = array(
    ':parent_id'=>$_POST['parent_id'],
    ':type'=>$_POST['type'],
    ':name'=>$_POST['name'],
    ':tag'=>$_POST['tag'],
    ':sort'=>$_POST['sort']
);
if(!Db::query($pdo, $sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$parentIds = $permissionParent['parent_ids'].','.$id;
$sql = "update permission set parent_ids = :parent_ids where id = :id";
$data = array(
    ':parent_ids'=>$parentIds,
    ':id'=>$id
);
if(!Db::query($pdo, $sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '添加成功';
echo json_encode($return);
?>