<?php
/**
 * 添加保存
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\MenuModel;
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
$menuModel = new MenuModel();
$menuParent = array(); // 上级菜单
$id = 0; // 添加菜单id
$parentIds = ''; // 所有上级菜单id
$data = array();

// 验证
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_menu')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

$validateService->rule = array(
    'parent_id' => 'number',
    'type' => 'require',
    'name' => 'require|max_length:32',
    'tag' => 'max_length:64',
    'icon_class' => 'max_length:64',
    'url' => 'max_length:255',
    'sort' => 'number|max_length:10'
);
$validateService->message = array(
    'parent_id.number' => '请选择上级菜单',
    'type.require' => '请选择菜单类型',
    'name.require' => '请输入菜单名称',
    'name.max_length' => '菜单名称不能大于32个字',
    'tag.max_length' => '菜单标识不能大于64个字',
    'icon_class.max_length' => '图标不能大于64个字',
    'url.max_length' => '导航URL不能大于255个字',
    'sort.number' => '排序必须是个数字',
    'sort.max_length' => '排序不能大于10个字'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}

// 上级菜单
$menuParent = $menuModel->selectRow(
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
    'icon_class'=>$_POST['icon_class'],
    'url'=>$_POST['url'],
    'permission'=>$_POST['permission'],
    'sort'=>$_POST['sort']
);
try{
    $id = $menuModel->insert($data);
}catch(Exception $e){
    $return['message'] = $e->getMessage();
    echo json_encode($return);
    exit;
}

$parentIds = $menuParent['parent_ids'].','.$id;
$menuModel->update(
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