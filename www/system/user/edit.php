<?php
/**
 * 修改
 */
require_once '../../library/app.php';

use library\Auth;
use library\Config;
use library\DbHelper;
use library\ArrayTwo;
use library\Validate;
use library\Safe;
use library\model\Dictionary;
use library\model\Department;

$validate = new Validate();
$dbHelper = new DbHelper();
$pdo = $dbHelper->getPdo();
$pdoStatement = null;
$config = Config::getAll();
$user = array();
$roles = array();
$radioStatus = '';
$optionRole = '';
$sql = '';
$data = array();
$department = new Department();
$dictionaryModel = new Dictionary();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_user')){
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

$sql = 'select id, username, name, phone, status_id, department_id, role_id_string from user where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$user = $dbHelper->fetch($pdoStatement);
if(empty($user)){
    header('location:../../error.php?message='.urlencode('没有找到用户'));
    exit;
}

$user['role_ids'] = explode(',', $user['role_id_string']);
$user['department_name'] = $department->getName($user['department_id']);
$user = Safe::entity($user);
$radioStatus = $dictionaryModel->getRadio('system_user_status', 'status_id', $user['status_id']);

$sql = 'select id, name from role order by id asc';
$pdoStatement = $dbHelper->query($pdo, $sql);
$roles = $dbHelper->fetchAll($pdoStatement);
$optionRole = ArrayTwo::getOption($roles, $user['role_ids'], 'id', 'name');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改用户</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/bootstrap-4.6.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/bootstrap-select-1.13.9/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/bootstrap-select-1.13.9/js/bootstrap-select.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/edit.js"></script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun-form-brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $user['id'];?>" />
<div class="field">
<div class="label"><span class="required">*</span> 用户名</div>
<div class="value">
<div class="body">
<?php echo $user['username'];?>
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 状态</div>
<div class="value">
<div class="body">
<?php echo $radioStatus;?>
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 密码</div>
<div class="value">
<div class="body">
<input type="password" name="password" id="password" autocomplete="off" />
<span class="tip">不修改请保持密码输入框为空</span>
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 姓名</div>
<div class="value">
<div class="body">
<input type="text" name="name" id="name" value="<?php echo $user['name'];?>" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 手机号码</div>
<div class="value">
<div class="body">
<input type="text" name="phone" id="phone" value="<?php echo $user['phone'];?>" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 部门</div>
<div class="value">
<div class="body">
<input type="hidden" name="department_id" id="department_id" value="<?php echo $user['department_id'];?>" />
<div class="sun-input-group" onClick="selectDepartment();">
<input type="text" name="department_name" id="department_name" readonly value="<?php echo $user['department_name'];?>" />
<div class="addon"><span class="iconfont icon-magnifier icon"></span></div>
</div>
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 角色</div>
<div class="value">
<div class="body">
<select name="role_ids[]" multiple="multiple" class="selectpicker role_ids" id="role_ids" data-live-search="true" title="请选择" data-width="170px">
<?php echo $optionRole;?>
</select>
</div>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_user_edit');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>