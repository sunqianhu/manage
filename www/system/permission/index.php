<?php
/**
 * 权限管理
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\PermissionModel;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\TreeService;
use library\service\SafeService;
use library\service\system\PermissionService;
use library\service\AuthService;

$config = ConfigService::getAll();
$permissionModel = new PermissionModel();
$permissions = array(); // 权限数据
$permissionNode = ''; // 权限表格节点
$frameMainMenu = '';
$search = array(
    'id'=>'',
    'name'=>'',
    'permission'=>''
);
$whereMarks = array();
$whereValues = array();
$where = array();

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_permission')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 权限
$frameMainMenu = FrameMainService::getPageLeftMenu('system_permission');

// 搜索
if(!empty($_GET['id'])){
    $whereMarks[] = 'id = :id';
    $whereValues[':id'] = $_GET['id'];
    $search['id'] = $_GET['id'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $whereMarks[] = 'name like :name';
    $whereValues[':name'] = '%'.$_GET['name'].'%';
    $search['name'] = $_GET['name'];
}
if(isset($_GET['permission']) && $_GET['permission'] !== ''){
    $whereMarks[] = 'permission like :permission';
    $whereValues[':permission'] = $_GET['permission'];
    $search['permission'] = $_GET['permission'];
}
$search = SafeService::frontDisplay($search);
if(!empty($whereMarks)){
    $where['mark'] = implode(' and ', $whereMarks);
}
$where['value'] = $whereValues;

// 数据
$permissions = $permissionModel->selectAll('id, parent_id, name, `sort`, tag', $where, '`sort` asc, id asc');
$permissions = TreeService::getTree($permissions, 'child', 'id', 'parent_id');
$permissions = SafeService::frontDisplay($permissions, 'id,parent_id');
$permissionNode = PermissionService::getIndexTreeNode($permissions, 1);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>权限管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/permission/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/permission/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../public/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 权限管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>权限id：<input type="text" name="id" value="<?php echo $search['id'];?>" /></li>
<li>权限名称：<input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>权限标识：<input type="text" name="permission" value="<?php echo $search['permission'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun-button" />
<input type="reset" class="sun-button plain sun-ml5" value="重置" />
</li>
</ul>
</form>
</div>

<div class="data sun-mt10">
<div class="toolbar">
<a href="javascript:;" class="sun-button" data-toggle="tooltip" title="添加权限" onClick="index.add(0);">添加</a>
</div>
<table class="sun-table-list hover sun-mt10 sun-treetable" width="100%">
  <tr>
    <th width="100">权限id</th>
    <th>权限名称</th>
    <th>权限标识</th>
    <th>排序</th>
    <th width="150">操作</th>
  </tr>
<?php echo $permissionNode;?>
</table>

</div>

</div>
</div>
</div>
</body>
</html>