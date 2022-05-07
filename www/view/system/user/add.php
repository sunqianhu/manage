<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加用户</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/select2-4.1.0/css/select2.min.css" rel="stylesheet" />
<script src="<?php echo $config['app_domain'];?>js/plug/select2-4.1.0/js/select2.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/add.js"></script>
</head>

<body class="page">
<form method="post" action="addSave" class="sun_form">
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 用户名</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 密码</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 确认密码</div>
<div class="sun_form_content">
<input type="text" name="xxx" id="xxx" />
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
<select name="role[]" multiple="multiple" class="role" id="role" style=" width: 300px">
  <option value="1">超级管理员</option>
  <option value="2">一般管理员</option>
</select>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label">备注</div>
<div class="sun_form_content">
<textarea name="xxx" id="xxx" class="remark" ></textarea>
</div>
</div>
</form>

</body>
</html>