<?php
/**
 * 修改密码
 */
require_once '../library/app.php';

use \library\Session;
use \library\OperationLog;
use \library\Config;
use \library\Auth;

Session::start();

$config = Config::getAll();

OperationLog::add();

// 验证
if(!Auth::isLogin()){
    header('location:../my/login.php');
    exit;
}

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改密码</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/my/edit_password.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/my/edit_password.js"></script>
</head>

<body class="page">
<form method="post" action="edit_password_save.php" class="sun-form-brief form">
<div class="page_body">
<div class="row">
<div class="title"><span class="required">*</span> 新密码</div>
<div class="content">
<input type="password" name="password" id="password" autocomplete="off" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 确认新密码</div>
<div class="content">
<input type="password" name="password2" id="password2" autocomplete="off" />
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_edit_password');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>