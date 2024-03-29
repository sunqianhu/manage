<?php
/**
 * 修改
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Config;
use library\core\Db;
use library\core\Ztree;
use library\core\Validate;
use library\core\Safe;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$config = Config::getAll();
$role = array();
$rolePermissions = array();
$permissionIds = array();
$permissions = array();
$permission = ''; // 权限json数据
$ztree = new Ztree();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$validate->setRule(array(
    'id' => 'require|number'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
));
if(!$validate->check($_GET)){
    header('location:../../error.php?message='.urlencode($validate->getErrorMessage()));
    exit;
}

$sql = 'select id, name, remark from role where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$role = $db->fetch($pdoStatement);
if(empty($role)){
    header('location:../../error.php?message='.urlencode('角色没有找到'));
    exit;
}

$sql = 'select permission_id from role_permission where role_id = :role_id';
$data = array(
    ':role_id'=>$role['id']
);
$pdoStatement = $db->query($pdo, $sql, $data);
$rolePermissions = $db->fetchAll($pdoStatement);
$permissionIds = array_column($rolePermissions, 'permission_id');
$role['permission_ids'] = implode(',', $permissionIds);
$role = Safe::entity($role);

$sql = 'select id, name, parent_id from permission where parent_id != 0 order by parent_id asc, id asc';
$pdoStatement = $db->query($pdo, $sql);
$permissions = $db->fetchAll($pdoStatement);
$permissions = $ztree->setOpenByFirst($permissions);
$permissions = $ztree->setChecked($permissions, $permissionIds);
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
var permissionData = <?php echo $permission;?>;
</script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun-form-brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $role['id'];?>" />
<div class="field">
<div class="label"><span class="required">*</span> 角色名称</div>
<div class="value">
<div class="body">
<input type="text" name="name" id="name" value="<?php echo $role['name'];?>" />
</div>
</div>
</div>

<div class="field">
<div class="label">备注</div>
<div class="value">
<div class="body">
<input type="text" name="remark" id="remark" value="<?php echo $role['remark'];?>" />
</div>
</div>
</div>

<div class="field">
<div class="label">权限</div>
<div class="value">
<div class="body">
<input type="hidden" name="permission_ids" id="permission_ids" value="<?php echo $role['permission_ids'];?>"  />
<div class="ztree" id="ztree_permission"></div>
</div>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('edit');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>