<?php
/**
 * 添加
 */
require_once '../../library/app.php';

use library\Auth;
use library\Config;
use library\DbHelper;
use library\ArrayTwo;
use library\model\Dictionary;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getPdo();
$pdoStatement = null;
$config = Config::getAll();
$radioStatus = '';
$optionRole = '';
$sql = '';
$data = array();
$dictionaryModel = new Dictionary();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$sql = "select id, name from role order by id asc";
$pdoStatement = $dbHelper->query($pdo, $sql);
$roles = $dbHelper->fetchAll($pdoStatement);
$optionRole = ArrayTwo::getOption($roles);

$radioStatus = $dictionaryModel->getRadio('system_user_status', 'status_id', 1);
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加用户</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/bootstrap-4.6.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/bootstrap-select-1.13.9/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/bootstrap-select-1.13.9/js/bootstrap-select.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun-form-brief form">
<div class="page_body">
<div class="field">
<div class="label"><span class="required">*</span> 用户名</div>
<div class="value">
<div class="body">
<input type="text" name="username" id="username" />
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
<input type="password" name="password" id="password" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 确认密码</div>
<div class="value">
<div class="body">
<input type="password" name="password2" id="password2" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 姓名</div>
<div class="value">
<div class="body">
<input type="text" name="name" id="name" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 手机号码</div>
<div class="value">
<div class="body">
<input type="text" name="phone" id="phone" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 部门</div>
<div class="value">
<div class="body">
<input type="hidden" name="department_id" id="department_id" value="0" />
<div class="sun-input-group" onClick="selectDepartment();">
<input type="text" name="department_name" id="department_name" readonly value="请选择" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
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
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('add');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>