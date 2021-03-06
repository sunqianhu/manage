<?php
/**
 * 修改
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\PermissionModel;
use library\service\ConfigService;
use library\service\ValidateService;
use library\service\SafeService;
use library\service\system\DictionaryService;
use library\service\AuthService;

$config = ConfigService::getAll();
$validateService = new ValidateService();
$permissionModel = new PermissionModel();
$permission = array();

// 验证
if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_permission')){
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
    header('location:../../error.php?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

$permission = $permissionModel->selectRow('id, parent_id, type, name, `sort`, tag', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
$permission['parent_name'] = $permissionModel->selectOne('name', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=> $permission['parent_id']
    )
));
$permission = SafeService::frontDisplay($permission);

$permissionTypeRadioNode = DictionaryService::getRadio('system_permission_type', 'type', $permission['type']);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改权限</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/permission/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/permission/edit.js"></script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun-form-brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $permission['id'];?>" />
<div class="row">
<div class="title"><span class="required">*</span> 权限组</div>
<div class="content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $permission['parent_id'];?>" />
<div class="sun-input-group" onClick="edit.selectPermission();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $permission['parent_name'];?>" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 权限类型</div>
<div class="content">
<?php echo $permissionTypeRadioNode;?>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 权限名称</div>
<div class="content">
<input type="text" name="name" id="name" value="<?php echo $permission['name'];?>" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 权限标识</div>
<div class="content">
<input type="text" name="tag" id="tag" value="<?php echo $permission['tag'];?>" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 排序</div>
<div class="content">
<input type="number" name="sort" id="sort" value="<?php echo $permission['sort'];?>" />
</div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button sun-button-secondary" onClick="window.parent.sun.layer.close('layer_permission_edit');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>