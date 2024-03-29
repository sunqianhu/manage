<?php
/**
 * 添加
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Config;

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_dictionary')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$config = Config::getAll();
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加字典</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/dictionary/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/dictionary/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun-form-brief form">
<div class="page_body">
<div class="field">
<div class="label"><span class="required">*</span> 字典类型</div>
<div class="value">
<div class="body">
<input type="text" name="type" id="type" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 字典键</div>
<div class="value">
<div class="body">
<input type="text" name="key" id="key" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 字典值</div>
<div class="value">
<div class="body">
<input type="text" name="value" id="value" />
</div>
</div>
</div>

<div class="field">
<div class="label"><span class="required">*</span> 排序</div>
<div class="value">
<div class="body">
<input type="number" name="sort" id="sort" value="1" />
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