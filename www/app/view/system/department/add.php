<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加部门</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/department/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/add.js"></script>
</head>

<body class="page">
<form method="post" action="department-add_save.json" class="sun_form form">
<div class="page_body">
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 上级部门</div>
<div class="sun_form_content">
<input type="hidden" name="parent_id" id="parent_id" value="1" />
<div class="sun_input_group" onClick="add.selectDepartment();">
<input type="text" name="parent_name" id="parent_name" readonly value="顶级部门" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 部门名称</div>
<div class="sun_form_content">
<input type="text" name="name" id="name" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 显示排序</div>
<div class="sun_form_content">
<input type="number" name="sort" id="sort" value="1" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">备注</div>
<div class="sun_form_content">
<textarea name="remark" id="remark" class="remark"></textarea>
</div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_department_add');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>