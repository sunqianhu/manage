<?php
/**
 * 自己修改密码
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\service\ConfigService;
use library\service\AuthService;

$config = ConfigService::getAll();

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改面</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/self_edit_password.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/self_edit_password.js"></script>
</head>

<body class="page">
<form method="post" action="self_edit_password_save.php" class="sun_form_brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $user['id'];?>" />
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
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_self_edit_password');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>