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
$dictionary = array();
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
if(!Auth::isPermission('system_dictionary')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validate->setRule(array(
    'id' => 'require|number',
    'type' => 'require|max_length:64',
    'key' => 'require|max_length:64',
    'value' => 'require|max_length:128',
    'sort' => 'number|max_length:10'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
    'type.require' => '请输入字典类型',
    'type.max_length' => '字典类型不能大于64个字',
    'key.require' => '请输入字典键',
    'key.max_length' => '字典键不能大于64个字',
    'value.require' => '请输入字典值',
    'value.max_length' => '字典值不能大于128个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
));
if(!$validate->check($_POST)){
    $return['message'] = $validate->getErrorMessage();
    $return['data']['dom'] = '#'.$validate->getErrorField();
    echo json_encode($return);
    exit;
}

// 本字典
$sql = 'select id from dictionary where id = :id';
$data = array(
    ':id'=>$_POST['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$dictionary = $db->fetch($pdoStatement);
if(empty($dictionary)){
    $return['message'] = '字典没有找到';
    echo json_encode($return);
    exit;
}

// 更新
$sql = 'update dictionary set
type = :type,
`key` = :key,
`value` = :value,
`sort` = :sort
where id = :id';
$data = array(
    ':type'=>$_POST['type'],
    ':key'=>$_POST['key'],
    ':value'=>$_POST['value'],
    ':sort'=>$_POST['sort'],
    ':id'=>$dictionary['id']
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