<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改角色</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.excheck.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/edit.js"></script>
<script type="text/javascript">
edit.menuData = <?php echo $menu;?>;
</script>
</head>

<body class="page">
<form method="post" action="role-edit_save.json" class="sun_form form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $role['id'];?>" />
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 角色名称</div>
<div class="sun_form_content">
<input type="text" name="name" id="name" value="<?php echo $role['name'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">备注</div>
<div class="sun_form_content">
<input type="text" name="remark" id="remark" value="<?php echo $role['remark'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">菜单权限</div>
<div class="sun_form_content">
<input type="hidden" name="menu_ids" id="menu_ids" value="<?php echo $role['menu_ids'];?>"  />
<div class="ztree" id="ztree_menu"></div>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_role_edit');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>