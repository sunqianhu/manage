<?php
/**
 * 修改保存
 */
require_once '../../library/app.php';

use \library\Db;
use \library\Validate;
use \library\Auth;

$return = array(
    'status'=>'error',
    'msg'=>'',
    'data'=>array(
        'dom'=>''
    )
); // 返回数据
$departmentCurrent = array(); // 本部门
$departmentParent = array(); // 上级部门
$sql = '';
$data = array();

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

Validate::setRule(array(
    'id' => 'require|number',
    'parent_id' => 'number',
    'name' => 'require|max_length:25',
    'sort' => 'number|max_length:10'
));
Validate::setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'parent_id.number' => '请选择上级部门',
    'name.require' => '请输入部门名称',
    'name.max_length' => '部门名称不能大于32个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
));
if(!Validate::check($_POST)){
    $return['message'] = Validate::getErrorMessage();
    $return['data']['dom'] = '#'.Validate::getErrorField();
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
$departmentCurrent = Db::selectRow($sql, $data);
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
$departmentParent = Db::selectRow($sql, $data);

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
if(!Db::update($sql, $data)){
    $return['message'] = Db::getError();
    echo json_encode($return);
    exit;
}

$return['status'] = 'success';
$return['message'] = '修改成功';
echo json_encode($return);
?>