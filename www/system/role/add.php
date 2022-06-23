<?php
/**
 * 添加
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\PermissionModel;
use library\service\ConfigService;
use library\service\ZtreeService;
use library\service\AuthService;

$config = ConfigService::getAll();
$permissionModel = new PermissionModel();
$permissions = array();
$permission = ''; // 权限json数据

if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$permissions = $permissionModel->select('id, name, parent_id', array(
    'mark'=>'parent_id != 0'
), 'order by parent_id asc, sort asc');
$permissions = ZtreeService::setOpenByFirst($permissions);
$permission = json_encode($permissions);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加角色</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.excheck.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/add.js"></script>
<script type="text/javascript">
add.permissionData = <?php echo $permission;?>;
</script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun_form_brief form">
<div class="page_body">
<div class="row">
<div class="title"><span class="required">*</span> 角色名称</div>
<div class="content">
<input type="text" name="name" id="name" maxlength="64" />
</div>
</div>

<div class="row">
<div class="title">备注</div>
<div class="content">
<input type="text" name="remark" id="remark" maxlength="255" />
</div>
</div>

<div class="row">
<div class="title">权限</div>
<div class="content">
<input type="hidden" name="permission_ids" id="permission_ids" />
<div class="ztree" id="ztree_permission"></div>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_role_add');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>