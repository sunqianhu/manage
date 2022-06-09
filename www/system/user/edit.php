<?php
/**
 * 修改
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\UserModel;
use library\model\system\RoleModel;
use library\model\system\DepartmentModel;
use library\service\ConfigService;
use library\service\ArrayTwoService;
use library\service\ValidateService;
use library\service\SafeService;
use library\service\system\DictionaryService;
use library\service\AuthService;

$config = ConfigService::getAll();
$validateService = new ValidateService();
$userModel = new UserModel();
$departmentModel = new DepartmentModel();
$roleModel = new RoleModel();
$user = array();
$roles = array();
$status = '';
$roleOption = '';

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
$validateService->rule = array(
    'id' => 'require|number'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
);
if(!$validateService->check($_GET)){
    header('location:../../error.html?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

$user = $userModel->selectRow('id, username, `name`, `phone`, `status`, department_id, role_id_string', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
if(empty($user)){
    header('location:../../error.html?message='.urlencode('没有找到用户'));
    exit;
}

$user['role_ids'] = explode(',', $user['role_id_string']);
$user['department_name'] = $departmentModel->selectOne('name', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$user['department_id']
    )
));
$user = SafeService::frontDisplay($user, array('id'));
$status = DictionaryService::getRadio('system_user_status', 'status', $user['status']);

$roles = $roleModel->select('id, name', array());
$roleOption = ArrayTwoService::getSelectOption($roles, $user['role_ids'], 'id', 'name');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改用户</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
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
<form method="post" action="edit_save.php" class="sun_form_brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $user['id'];?>" />
<div class="row">
<div class="title"><span class="required">*</span> 用户名</div>
<div class="content"><?php echo $user['username'];?></div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 状态</div>
<div class="content">
<?php echo $status;?>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 密码</div>
<div class="content">
<input type="password" name="password" id="password" autocomplete="off" />
<span class="tip">不修改请保持密码输入框为空</span>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 姓名</div>
<div class="content">
<input type="text" name="name" id="name" value="<?php echo $user['name'];?>" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 手机号码</div>
<div class="content">
<input type="text" name="phone" id="phone" value="<?php echo $user['phone'];?>" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 部门</div>
<div class="content">
<input type="hidden" name="department_id" id="department_id" value="<?php echo $user['department_id'];?>" />
<div class="sun_input_group" onClick="edit.selectDepartment();">
<input type="text" name="department_name" id="department_name" readonly value="<?php echo $user['department_name'];?>" />
<div class="addon"><span class="iconfont icon-magnifier icon"></span></div>
</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 角色</div>
<div class="content">
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