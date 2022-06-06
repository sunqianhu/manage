<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改菜单</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/menu/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/menu/edit.js"></script>
</head>

<body class="page">
<form method="post" action="menu-edit_save.json" class="sun_form form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $menu['id'];?>" />
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 上级菜单</div>
<div class="sun_form_content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $menu['parent_id'];?>" />
<div class="sun_input_group" onClick="edit.selectMenu();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $menu['parent_name'];?>" />
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
<input type="text" name="name" id="name" value="<?php echo $menu['name'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">url</div>
<div class="sun_form_content">
<textarea name="url" id="url" class="url"><?php echo $menu['url'];?></textarea>
<div class="sun_form_tip">设置可访问的url，一行一个url。</div>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 排序</div>
<div class="sun_form_content">
<input type="number" name="sort" id="sort" value="<?php echo $menu['sort'];?>" />
</div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_menu_edit');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>