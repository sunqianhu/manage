<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加用户</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link rel="stylesheet" href="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/css/bootstrap.min.css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="<?php echo $config['app_domain'];?>js/plug/bootstrap-select-1.13.9/css/bootstrap-select.min.css" />
<script src="<?php echo $config['app_domain'];?>js/plug/bootstrap-select-1.13.9/js/bootstrap-select.min.js"></script>
<script src="<?php echo $config['app_domain'];?>js/plug/bootstrap-select-1.13.9/js/i18n/defaults-zh_CN.min"></script>

<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/add.js"></script>
</head>

<body class="page">
<form method="post" action="addSave" class="sun_form">
<div class="page_body">
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 用户名</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 密码</div>
<div class="sun_form_content">
<input type="password" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 确认密码</div>
<div class="sun_form_content">
<input type="password" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 姓名</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">手机号码</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 部门</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 角色</div>
<div class="sun_form_content">
<select name="role[]" multiple="multiple" class="selectpicker role" id="role" data-live-search="true" title="请选择" data-width="170px">
<option value="1">超级管理员</option>
<option value="2">一般管理员</option>
</select>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">备注</div>
<div class="sun_form_content">
<textarea name="xxx" id="xxx" class="remark"></textarea>
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