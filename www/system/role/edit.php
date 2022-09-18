<?php
/**
 * 修改
 */
require_once '../../library/app.php';

use \library\Session;
use \library\OperationLog;
use \library\Db;
use \library\Config;
use \library\Ztree;
use \library\Validate;
use \library\Safe;
use \library\Auth;

Session::start();

$pdo = Db::getInstance();
$pdoStatement = null;
$config = Config::getAll();
$role = array();
$rolePermissions = array();
$permissionIds = array();
$permissions = array();
$permission = ''; // 权限json数据
$sql = '';
$data = array();

OperationLog::add();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

Validate::setRule(array(
    'id' => 'require|number'
));
Validate::setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
));
if(!Validate::check($_GET)){
    header('location:../../error.php?message='.urlencode(Validate::getErrorMessage()));
    exit;
}

$sql = 'select id, name, remark from role where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$role = Db::fetch($pdoStatement);
if(empty($role)){
    header('location:../../error.php?message='.urlencode('角色没有找到'));
    exit;
}

$sql = 'select permission_id from role_permission where role_id = :role_id';
$data = array(
    ':role_id'=>$role['id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$rolePermissions = Db::fetchAll($pdoStatement);
$permissionIds = array_column($rolePermissions, 'permission_id');
$role['permission_ids'] = implode(',', $permissionIds);
$role = Safe::entity($role);

$sql = 'select id, name, parent_id from permission where parent_id != 0 order by parent_id asc, id asc';
$pdoStatement = Db::query($pdo, $sql);
$permissions = Db::fetchAll($pdoStatement);
$permissions = Ztree::setOpenByFirst($permissions);
$permissions = Ztree::setChecked($permissions, $permissionIds);
$permission = json_encode($permissions);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改角色</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.excheck.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/edit.js"></script>
<script type="text/javascript">
edit.permissionData = <?php echo $permission;?>;
</script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun-form-brief form">
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
<div class="title">权限</div>
<div class="content">
<input type="hidden" name="permission_ids" id="permission_ids" value="<?php echo $role['permission_ids'];?>"  />
<div class="ztree" id="ztree_permission"></div>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_role_edit');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>