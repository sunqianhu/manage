<?php
/**
 * 添加
 */
require_once '../../library/app.php';

use library\Session;
use library\Auth;
use library\Db;
use library\OperationLog;
use library\Config;
use library\Validate;
use library\Safe;

$pdo = Db::getInstance();
$pdoStatement = null;
$sql = '';
$validate = new Validate();
$data = array();
$config = array();
$departmentParent = array();
$init = array(
    'parent_id'=>1,
    'parent_name'=>'顶级部门',
);

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_department')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
$validate->setRule(array(
    'parent_id' => 'number'
));
$validate->setMessage(array(
    'parent_id.number' => 'parent_id必须是个数字'
));
if(!$validate->check($_GET)){
    header('location:../../error.php?message='.urlencode($validate->getErrorMessage()));
    exit;
}

if(!empty($_GET['parent_id'])){
    $sql = 'select id, name from department where id = :id';
    $data = array(
        ':id'=>$_GET['parent_id']
    );
    $pdoStatement = Db::query($pdo, $sql, $data);
    $departmentParent = Db::fetch($pdoStatement);
    if(!empty($departmentParent)){
        $init['parent_id'] = $departmentParent['id'];
        $init['parent_name'] = $departmentParent['name'];
    }
    $init = Safe::entity($init);
}
$config = Config::getAll();
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加部门</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/department/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun-form-brief form">
<div class="page_body">
<div class="row">
<div class="title"><span class="required">*</span> 上级部门</div>
<div class="content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $init['parent_id'];?>" />
<div class="sun-input-group" onClick="add.selectDepartment();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $init['parent_name'];?>" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 部门名称</div>
<div class="content">
<input type="text" name="name" id="name" />
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 显示排序</div>
<div class="content">
<input type="number" name="sort" id="sort" value="1" />
</div>
</div>

<div class="row">
<div class="title">备注</div>
<div class="content">
<textarea name="remark" id="remark" class="remark"></textarea>
</div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_department_add');">关闭</a>
<input type="submit" class="sun-button" value="提交" />
</div>
</form>
</body>
</html>