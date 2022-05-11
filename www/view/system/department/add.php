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
<form method="post" action="addSave" class="sun_form">
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 上级部门</div>
<div class="sun_form_content">

<div class="sun_input_group" onClick="add.selectDepartment();">
<input type="text" name="xxx" id="xxx" />
<div class="sun_input_group_icon_right"><span class="iconfont icon-magnifier icon"></span></div>
</div>

</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 部门名称</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 显示排序</div>
<div class="sun_form_content">
<input type="number" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">备注</div>
<div class="sun_form_content">
<textarea name="xxx" id="xxx" class="remark"></textarea>
</div>
</div>
</form>

</body>
</html>