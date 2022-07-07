<?php
/**
 * 选择上级权限
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\PermissionModel;
use library\service\ConfigService;
use library\service\ZtreeService;
use library\service\AuthService;

$config = ConfigService::getAll();
$permissionModel = new PermissionModel();
$permissions = array(); // 权限数据
$permission = ''; // 权限json数据

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_permission')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$permissions = $permissionModel->select('id, name, parent_id', array(), 'order by parent_id asc, id asc');
$permissions = ZtreeService::setOpenByFirst($permissions);
$permission = json_encode($permissions);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选择上级权限</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/permission/edit_select_permission.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/permission/edit_select_permission.js"></script>
<script type="text/javascript">
editSelectPermission.permissionData = <?php echo $permission;?>;
</script>
</head>

<body class="page">
<div class="page_body">
<ul id="ztree" class="ztree"></ul>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button sun-button-secondary" onClick="window.parent.sun.layer.close('layer_edit_select_permission');">关闭</a>
<input type="button" class="sun-button" value="确定" onClick="editSelectPermission.submit();" />
</div>
</body>
</html>