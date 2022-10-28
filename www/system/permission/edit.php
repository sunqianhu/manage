<?php
/**
 * 修改
 */
require_once '../../library/app.php';

use library\Auth;
use library\Config;
use library\DbHelper;
use library\Validate;
use library\Safe;
use library\model\Dictionary;
use library\model\Permission;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$config = Config::getAll();
$permission = array();
$permissionModel = new Permission();
$dictionaryModel = new Dictionary();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_permission')){
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

$sql = 'select id, parent_id, name, `sort`, tag from permission where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$permission = $dbHelper->fetch($pdoStatement);

$permission['parent_name'] = $permissionModel->getName($permission['parent_id']);
$permission = Safe::entity($permission);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改权限</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/permission/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/permission/edit.js"></script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun-form-brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $permission['id'];?>" />
<div class="field">
<div class="label"><span class="required">*</span> 权限组</div>
<div class="value">
<div class="body">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $permission['parent_id'];?>" />
<div class="sun-input-group" onClick="edit.selectPermission();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $permission['parent_name'];?>" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 权限名称</div>
<div class="value">
<div class="body">
<input type="text" name="name" id="name" value="<?php echo $permission['name'];?>" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 权限标识</div>
<div class="value">
<div class="body">
<input type="text" name="tag" id="tag" value="<?php echo $permission['tag'];?>" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 排序</div>
<div class="value">
<div class="body">
<input type="number" name="sort" id="sort" value="<?php echo $permission['sort'];?>" />
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