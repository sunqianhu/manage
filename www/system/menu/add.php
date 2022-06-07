<?php
/**
 * 添加
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\service\ConfigService;
use library\service\system\DictionaryService;
use library\service\AuthService;

$config = ConfigService::getAll();
$menuTypeRadioNode = DictionaryService::getRadio('system_menu_type', 'type', 1);

if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_menu')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加菜单</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/menu/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/menu/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun_form form">
<div class="page_body">
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 上级菜单</div>
<div class="sun_form_content">
<input type="hidden" name="parent_id" id="parent_id" value="1" />
<div class="sun_input_group" onClick="add.selectMenu();">
<input type="text" name="parent_name" id="parent_name" readonly value="顶级菜单" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 菜单类型</div>
<div class="sun_form_content">
<?php echo $menuTypeRadioNode;?>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 菜单名称</div>
<div class="sun_form_content">
<input type="text" name="name" id="name" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">菜单标识</div>
<div class="sun_form_content">
<input type="text" name="tag" id="tag" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">图标</div>
<div class="sun_form_content">
<input type="text" name="icon_class" id="icon_class" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">导航URL</div>
<div class="sun_form_content">
<input type="text" name="url" id="url" style="width: 300px" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">权限标识</div>
<div class="sun_form_content">
<input type="text" name="permission" id="permission" class="permission" />
<div class="sun_form_tip">例如：“system_user_add”，从根开始写。</div>
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
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_menu_add');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>