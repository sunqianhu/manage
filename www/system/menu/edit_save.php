<?php
/**
 * 修改保存
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

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
$menuCurrent = array(); // 本菜单
$menuParent = array(); // 上级菜单
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
    'id' => 'require|number',
    'parent_id' => 'number',
    'type' => 'require',
    'name' => 'require|max_length:32',
    'tag' => 'max_length:64',
    'icon_class' => 'max_length:64',
    'url' => 'max_length:255',
    'sort' => 'number|max_length:10'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字',
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

// 本菜单
$menuCurrent = $menuModel->selectRow(
    'id, parent_id',
    array(
        'mark'=> 'id = :id',
        'value'=> array(
            ':id'=>$_POST['id']
        )
    )
);
if(empty($menuCurrent)){
    $return['message'] = '此菜单没有找到';
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

// 更新
$data = array(
    'parent_id'=>$_POST['parent_id'],
    'parent_ids'=>$menuParent['parent_ids'].','.$menuCurrent['id'],
    'name'=>$_POST['name'],
    'type'=>$_POST['type'],
    'tag'=>$_POST['tag'],
    'icon_class'=>$_POST['icon_class'],
    'url'=>$_POST['url'],
    'permission'=>$_POST['permission'],
    'sort'=>$_POST['sort']
);
try{
    $id = $menuModel->update($data, array(
        'mark'=>'id = :id',
        'value'=> array(
            ':id'=>$menuCurrent['id']
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