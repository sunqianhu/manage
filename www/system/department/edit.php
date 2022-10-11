<?php
/**
 * 修改
 */
require_once '../../library/app.php';

use library\Session;
use library\Auth;
use library\DbHelper;
use library\OperationLog;
use library\Config;
use library\Validate;
use library\Safe;
use library\Department;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getInstance();
$pdoStatement = null;
$sql = '';
$validate = new Validate();
$data = array();
$config = Config::getAll();
$department = array();
$departmentObject = new Department();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_department')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$validate->setRule(array(
    'id' => 'require|number'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
));
if(!$validate->check($_GET)){
    header('location:../../error.php?message='.urlencode($validate->getErrorMessage()));
    exit;
}
if($_GET['id'] == '1'){
    header('location:../../error.php?message='.urlencode('根部门不能修改'));
    exit;
}

$sql = 'select id, parent_id, name, `sort`, remark from department where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$department = $dbHelper->fetch($pdoStatement);
$department['parent_name'] = $departmentObject->getName($department['parent_id']);
$department = Safe::entity($department);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改部门</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/department/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/edit.js"></script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun-form-brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $department['id'];?>" />
<div class="row">
<div class="title"><span class="required">*</span> 上级部门</div>
<div class="content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $department['parent_id'];?>" />
<div class="sun-input-group" onClick="edit.selectDepartment();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $department['parent_name'];?>" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 部门名称</div>
<div class="content">
<input type="text" name="name" id="name" value="<?php echo $department['name'];?>" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 显示排序</div>
<div class="content">
<input type="number" name="sort" id="sort" value="<?php echo $department['sort'];?>" />
</div>
</div>

<div class="row">
<div class="title">备注</div>
<div class="content">
<textarea name="remark" id="remark" class="remark"><?php echo $department['remark'];?></textarea>
</div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_department_edit');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>