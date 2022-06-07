<?php
/**
 * 添加
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\service\ConfigService;
use library\service\AuthService;

if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_dictionary')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$config = ConfigService::getAll();
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加字典</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/dictionary/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/dictionary/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun_form form">
<div class="page_body">
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 字典类型</div>
<div class="sun_form_content">
<input type="text" name="type" id="type" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 字典键</div>
<div class="sun_form_content">
<input type="text" name="key" id="key" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 字典值</div>
<div class="sun_form_content">
<input type="text" name="value" id="value" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 排序</div>
<div class="sun_form_content">
<input type="number" name="sort" id="sort" value="1" />
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_dictionary_add');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>