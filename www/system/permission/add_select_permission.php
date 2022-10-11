<?php
/**
 * 选择上级权限
 */
require_once '../../library/app.php';

use library\Session;
use library\Auth;
use library\Db;
use library\Config;
use library\Ztree;

$pdo = Db::getInstance();
$pdoStatement = null;
$sql = '';
$config = Config::getAll();
$permissions = array();
$permission = ''; // 权限json数据
$ztree = new Ztree();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_permission')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$sql = "select id, name, parent_id from permission order by parent_id asc, id asc";
$pdoStatement = Db::query($pdo, $sql);
$permissions = Db::fetchAll($pdoStatement);
$permissions = $ztree->setOpenByFirst($permissions);
$permission = json_encode($permissions);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选择上级权限</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/permission/add_select_permission.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/permission/add_select_permission.js"></script>
<script type="text/javascript">
addSelectPermission.permissionData = <?php echo $permission;?>;
</script>
</head>

<body class="page">
<div class="page_body">
<ul id="ztree" class="ztree"></ul>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_add_select_permission');">关闭</a>
<input type="button" class="sun-button" value="确定" onClick="addSelectPermission.submit();" />
</div>
</body>
</html>