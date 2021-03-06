<?php
/**
 * 角色管理
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\RoleModel;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\SafeService;
use library\service\PaginationService;
use library\service\AuthService;

$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架权限
$roleModel = new RoleModel(); // 模型
$search = array(
    'id'=>'',
    'name'=>''
); // 搜索
$whereMarks = array();
$whereValues = array();
$where = array();
$paginationService = null; // 分页
$recordTotal = 0; // 总记录
$paginationNodeIntact = ''; // 节点
$roles = array();

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 权限
$frameMainMenu = FrameMainService::getPageLeftMenu('system_role');

// 搜索
if(!empty($_GET['id'])){
    $whereMarks[] = 'id = :id';
    $whereValues[':id'] = $_GET['id'];
    $search['id'] = $_GET['id'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $whereMarks[] = 'name = :name';
    $whereValues[':name'] = '%'.$_GET['name'].'%';
    $search['name'] = $_GET['name'];
}
if(!empty($whereMarks)){
    $where['mark'] = implode(' and ', $whereMarks);
}
if(!empty($whereMarks)){
    $where['value'] = $whereValues;
}
$recordTotal = $roleModel->selectOne('count(1)', $where);

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

$roles = $roleModel->selectAll('id, name, time_edit', $where, 'id asc', ''.$paginationService->limitStart.','.$paginationService->pageSize);
foreach($roles as $key => $role){
    $roles[$key]['time_edit_name'] = date('Y-m-d H:i:s', $role['time_edit']);
}

$search = SafeService::frontDisplay($search);
$roles = SafeService::frontDisplay($roles);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>角色管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../public/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 角色管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>角色id：<input type="text" name="id" value="<?php echo $search['id'];?>" /></li>
<li>角色名称：<input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun-button" />
</li>
</ul>
</form>
</div>

<div class="data sun-mt10">
<div class="toolbar">
<a href="javascript:;" class="sun-button" onClick="index.add();">添加</a>
</div>
<table class="sun-table-list sun-table-list-hover sun-mt10" width="100%">
  <tr>
    <th>id</th>
    <th>角色名称</th>
    <th>最后修改时间</th>
    <th width="90">操作</th>
  </tr>
<?php
if(!empty($roles)){
foreach($roles as $role){
?>
  <tr>
    <td><?php echo $role['id'];?></td>
    <td><?php echo $role['name'];?></td>
    <td><?php echo $role['time_edit_name'];?></td>
    <td>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small sun-mr5" onClick="index.edit(<?php echo $role['id'];?>)">修改</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small" onClick="index.delete(<?php echo $role['id'];?>)">删除</a>
    </td>
  </tr>
<?php
}
}else{
?>
<tr>
<td colspan="5" align="center">无数据</td>
</tr>
<?php
}
?>
</table>
<?php echo $paginationNodeIntact;?>
</div>

</div>
</div>
</div>
</body>
</html>