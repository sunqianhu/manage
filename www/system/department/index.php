<?php
/**
 * 部门管理
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Config;
use library\core\Db;
use library\helper\FrameMain;
use library\core\Tree;
use library\core\Safe;
use library\helper\Department;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$config = Config::getAll();
$departments = array(); // 部门数据
$departmentNode = ''; // 部门表格节点
$frameMain = new FrameMain();
$frameMainMenu = '';
$search = array(
    'id'=>'',
    'name'=>'',
    'remark'=>''
);
$wheres = array();
$where = '1';
$departmentObject = new Department();
$tree = new Tree();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_department')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 菜单
$frameMainMenu = $frameMain->getMenu('system_department');

// 搜索
if(!empty($_GET['id'])){
    $wheres[] = 'id = :id';
    $data[':id'] = $_GET['id'];
    $search['id'] = $_GET['id'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $wheres[] = 'name like :name';
    $data[':name'] = '%'.$_GET['name'].'%';
    $search['name'] = $_GET['name'];
}
if(isset($_GET['remark']) && $_GET['remark'] !== ''){
    $wheres[] = 'remark like :remark';
    $data[':remark'] = '%'.$_GET['remark'].'%';
    $search['remark'] = $_GET['remark'];
}
$search = Safe::entity($search);

if(!empty($wheres)){
    $where = implode(' and ', $wheres);
}

// 数据
$sql = "select id, parent_id, name, `sort`, remark from department where $where order by `sort` asc, id asc";
$pdoStatement = $db->query($pdo, $sql, $data);
$departments = $db->fetchAll($pdoStatement);
$departments = $tree->getTree($departments, 'child', 'id', 'parent_id');
$departments = Safe::entity($departments);
$departmentNode = $departmentObject->getIndexTreeNode($departments, 1);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>部门管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/public/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/department/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/index.js"></script>
</head>

<body class="page">
<?php require_once '../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once '../../public/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 部门管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>部门id <input type="text" name="id" value="<?php echo $search['id'];?>" /></li>
<li>部门名称 <input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>备注 <input type="text" name="remark" value="<?php echo $search['remark'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun-button" />
<input type="reset" class="sun-button plain sun-ml5" value="重置" />
</li>
</ul>
</form>
</div>

<div class="data sun-mt10">
<div class="toolbar">
<a href="javascript:;" class="sun-button" data-toggle="tooltip" title="添加部门" onClick="add(0);">添加</a>
</div>
<table class="sun-table-list hover sun-treetable sun-mt10" width="100%">
  <tr>
    <th width="100">部门id</th>
    <th>部门名称</th>
    <th>排序</th>
    <th>备注</th>
    <th width="150">操作</th>
  </tr>
<?php echo $departmentNode;?>
</table>

</div>

</div>
</div>
</div>
</body>
</html>