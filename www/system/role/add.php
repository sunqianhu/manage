<?php
/**
 * 添加
 */
require_once '../../library/app.php';

use \library\Session;
use \library\Auth;
use \library\Db;
use \library\OperationLog;
use \library\Config;
use \library\Ztree;

Session::start();

$pdo = Db::getInstance();
$pdoStatement = null;
$sql = '';
$config = Config::getAll();
$permissions = array();
$permission = ''; // 权限json数据

OperationLog::add();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$sql = 'select id, name, parent_id from permission where parent_id != 0 order by parent_id asc, sort asc';
$pdoStatement = Db::query($pdo, $sql);
$permissions = Db::fetchAll($pdoStatement);
$permissions = Ztree::setOpenByFirst($permissions);
$permission = json_encode($permissions);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加角色</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.excheck.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/add.js"></script>
<script type="text/javascript">
add.permissionData = <?php echo $permission;?>;
</script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun-form-brief form">
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
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_role_add');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>