<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改用户</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>

<link href="<?php echo $config['app_domain'];?>js/plug/bootstrap-select-1.13.9/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/bootstrap-select-1.13.9/js/bootstrap-select.min.js"></script>

<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/edit.js"></script>
</head>

<body class="page">
<form method="post" action="user-edit_save.json" class="sun_form form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $user['id'];?>" />
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 用户名</div>
<div class="sun_form_content"><?php echo $user['username'];?></div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 状态</div>
<div class="sun_form_content">
<?php echo $status;?>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 密码</div>
<div class="sun_form_content">
<input type="password" name="password" id="password" autocomplete="off" />
<span class="sun_form_tip">不修改请保持密码输入框为空</span>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 姓名</div>
<div class="sun_form_content">
<input type="text" name="name" id="name" value="<?php echo $user['name'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 手机号码</div>
<div class="sun_form_content">
<input type="text" name="phone" id="phone" value="<?php echo $user['phone'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 部门</div>
<div class="sun_form_content">
<input type="hidden" name="department_id" id="department_id" value="<?php echo $user['department_id'];?>" />
<div class="sun_input_group" onClick="edit.selectDepartment();">
<input type="text" name="department_name" id="department_name" readonly value="<?php echo $user['department_name'];?>" />
<div class="addon"><span class="iconfont icon-magnifier icon"></span></div>
</div>
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 角色</div>
<div class="sun_form_content">
<select name="role_ids[]" multiple="multiple" class="selectpicker role_ids" id="role_ids" data-live-search="true" title="请选择" data-width="170px">
<?php echo $roleOption;?>
</select>
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_user_edit');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>