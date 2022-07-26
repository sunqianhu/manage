<?php
/**
 * 添加
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\PermissionModel;
use library\service\ConfigService;
use library\service\system\DictionaryService;
use library\service\AuthService;
use library\service\ValidateService;
use library\service\SafeService;

$permissionModel = new PermissionModel();
$config = ConfigService::getAll();
$validateService = new ValidateService();
$permissionTypeRadioNode = DictionaryService::getRadio('system_permission_type', 'type', 1);
$permissionParent = array();
$init = array(
    'parent_id'=>1,
    'parent_name'=>'顶级权限',
);

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_permission')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
$validateService->rule = array(
    'parent_id' => 'number'
);
$validateService->message = array(
    'parent_id.number' => 'parent_id必须是个数字'
);
if(!$validateService->check($_GET)){
    header('location:../../error.php?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

if(!empty($_GET['parent_id'])){
    $permissionParent = $permissionModel->selectRow('id, name', array(
        'mark'=>'id = :id',
        'value'=>array(
            ':id'=> $_GET['parent_id']
        )
    ));
    if(!empty($permissionParent)){
        $init['parent_id'] = $permissionParent['id'];
        $init['parent_name'] = $permissionParent['name'];
    }
    $init = SafeService::frontDisplay($init);
}

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加权限</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/permission/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/permission/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun-form-brief form">
<div class="page_body">
<div class="row">
<div class="title"><span class="required">*</span> 上级权限</div>
<div class="content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $init['parent_id'];?>" />
<div class="sun-input-group" onClick="add.selectPermission();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $init['parent_name'];?>" />
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
<input type="text" name="name" id="name" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 权限标识</div>
<div class="content">
<input type="text" name="tag" id="tag" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 排序</div>
<div class="content">
<input type="number" name="sort" id="sort" value="1" />
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_permission_add');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>