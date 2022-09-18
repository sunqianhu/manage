<?php
/**
 * 角色管理
 */
require_once '../../library/app.php';

use \library\Session;
use \library\OperationLog;
use \library\Db;
use \library\Config;
use \library\FrameMain;
use \library\Safe;
use \library\Pagination;
use \library\Auth;

Session::start();

$pdo = Db::getInstance();
$pdoStatement = null;
$config = Config::getAll();
$frameMainMenu = ''; // 框架权限
$search = array(
    'id'=>'',
    'name'=>''
); // 搜索
$wheres = array();
$where = '1';
$recordTotal = 0; // 总记录
$pagination = null; // 分页
$paginationNodeIntact = ''; // 节点
$roles = array();
$sql = '';
$data = array();

OperationLog::add();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_role')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 权限
$frameMainMenu = FrameMain::getMenu('system_role');

// 搜索
if(!empty($_GET['id'])){
    $wheres[] = 'id = :id';
    $data[':id'] = $_GET['id'];
    $search['id'] = $_GET['id'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $wheres[] = 'name = :name';
    $data[':name'] = '%'.$_GET['name'].'%';
    $search['name'] = $_GET['name'];
}
if(!empty($wheres)){
    $where = implode(' and ', $wheres);
}

$sql = "select count(1) from role where $where";
$pdoStatement = Db::query($pdo, $sql, $data);
$recordTotal = Db::fetchColumn($pdoStatement);

$pagination = new Pagination($recordTotal);
$paginationNodeIntact = $pagination->getNodeIntact();

$sql = "select id, name, time_edit from role where $where order by id asc limit ".$pagination->limitStart.','.$pagination->pageSize;
$pdoStatement = Db::query($pdo, $sql, $data);
$roles = Db::fetchAll($pdoStatement);
foreach($roles as $key => $role){
    $roles[$key]['time_edit_name'] = date('Y-m-d H:i:s', $role['time_edit']);
}

$search = Safe::entity($search);
$roles = Safe::entity($roles);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>角色管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/index.js"></script>
</head>

<body class="page">
<?php require_once '../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once '../../public/frame_main_left.php';?>
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
<table class="sun-table-list hover sun-mt10" width="100%">
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
<a href="javascript:;" class="sun-button plain small sun-mr5" onClick="index.edit(<?php echo $role['id'];?>)">修改</a>
<a href="javascript:;" class="sun-button plain small" onClick="index.delete(<?php echo $role['id'];?>)">删除</a>
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