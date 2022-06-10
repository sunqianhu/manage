<?php
/**
 * 修改
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\RoleModel;
use library\model\system\MenuModel;
use library\model\system\RoleMenuModel;
use library\service\ConfigService;
use library\service\ZtreeService;
use library\service\ValidateService;
use library\service\SafeService;
use library\service\AuthService;

$config = ConfigService::getAll();
$validateService = new ValidateService();
$roleModel = new RoleModel();
$roleMenuModel = new RoleMenuModel();
$menuModel = new MenuModel();
$role = array();
$roleMenus = array();
$roleMenuIds = array();
$menus = array();
$menu = ''; // 菜单json数据

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$validateService->rule = array(
    'id' => 'require|number'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
);
if(!$validateService->check($_GET)){
    header('location:../../error.php?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

$role = $roleModel->selectRow('id, name, remark', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
if(empty($role)){
    header('location:../../error.php?message='.urlencode('id参数错误'));
    exit;
}

$roleMenus = $roleMenuModel->select('menu_id', array(
    'mark'=>'role_id = :role_id',
    'value'=>array(
        ':role_id'=>$role['id']
    )
));
$roleMenuIds = array_column($roleMenus, 'menu_id');
$role['menu_ids'] = implode(',', $roleMenuIds);
$role = SafeService::frontDisplay($role, array('id'));

$menus = $menuModel->select('id, name, parent_id', array(
    'mark'=>'parent_id != 0'
), 'order by parent_id asc, id asc');
$menus = ZtreeService::setOpenByFirst($menus);
$menus = ZtreeService::setChecked($menus, $roleMenuIds);
$menu = json_encode($menus);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改角色</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.excheck.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/edit.js"></script>
<script type="text/javascript">
edit.menuData = <?php echo $menu;?>;
</script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun_form_brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $role['id'];?>" />
<div class="row">
<div class="title"><span class="required">*</span> 角色名称</div>
<div class="content">
<input type="text" name="name" id="name" value="<?php echo $role['name'];?>" />
</div>
</div>

<div class="row">
<div class="title">备注</div>
<div class="content">
<input type="text" name="remark" id="remark" value="<?php echo $role['remark'];?>" />
</div>
</div>

<div class="row">
<div class="title">菜单权限</div>
<div class="content">
<input type="hidden" name="menu_ids" id="menu_ids" value="<?php echo $role['menu_ids'];?>"  />
<div class="ztree" id="ztree_menu"></div>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_role_edit');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>