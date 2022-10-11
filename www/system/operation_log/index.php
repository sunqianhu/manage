<?php
/**
 * 操作日志
 */
require_once '../../library/app.php';

use library\Auth;
use library\DbHelper;
use library\Config;
use library\FrameMain;
use library\Pagination;
use library\Safe;
use library\MyString;
use library\User;
use library\Department;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getInstance();
$pdoStatement = null;
$sql = '';
$data = array();
$config = Config::getAll();
$frameMain = new FrameMain();
$frameMainMenu = ''; // 框架菜单
$search = array(
    'time_start'=>'',
    'time_end'=>'',
    'department_name'=>'',
    'user_name'=>''
); // 搜索
$wheres = array();
$where = '1';
$recordTotal = 0; // 总记录
$pagination = null; // 分页
$paginationNodeIntact = ''; // 节点
$operationLogs = array();
$department = new Department();
$user = new User();

if(!Auth::isLogin()){
    header('location:../../operation/index.php');
    exit;
}
if(!Auth::isPermission('system_operation_log')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 菜单
$frameMainMenu = $frameMain->getMenu('system_operation_log');

// 搜索
if(isset($_GET['time_start']) && $_GET['time_start'] !== ''){
    $wheres[] = 'time_add > :time_start';
    $data[':time_start'] = strtotime($_GET['time_start']);
    $search['time_start'] = $_GET['time_start'];
}
if(isset($_GET['time_end']) && $_GET['time_end'] !== ''){
    $wheres[] = 'time_add < :time_end';
    $data[':time_end'] = strtotime($_GET['time_end']);
    $search['time_end'] = $_GET['time_end'];
}
if(isset($_GET['ip']) && $_GET['ip'] !== ''){
    $wheres[] = 'ip = :ip';
    $data[':ip'] = $_GET['ip'];
    $search['ip'] = $_GET['ip'];
}
if(isset($_GET['department_name']) && $_GET['department_name'] !== ''){
    $wheres[] = 'department_id in (select id from department where name like :department_name)';
    $data[':department_name'] = '%'.$_GET['department_name'].'%';
    $search['department_name'] = $_GET['department_name'];
}
if(isset($_GET['user_id']) && $_GET['user_id'] !== ''){
    $wheres[] = 'user_id = :user_id';
    $data[':user_id'] = $_GET['user_id'];
}
if(isset($_GET['user_name']) && $_GET['user_name'] !== ''){
    $wheres[] = 'user_id in (select id from user where name like :user_name)';
    $data[':user_name'] = '%'.$_GET['user_name'].'%';
    $search['user_name'] = $_GET['user_name'];
}
if(!empty($wheres)){
    $where = implode(' and ', $wheres);
}

$sql = "select count(1) from login_log where $where";
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$recordTotal = $dbHelper->fetchColumn($pdoStatement);

$pagination = new Pagination($recordTotal);
$paginationNodeIntact = $pagination->getNodeIntact();

$sql = "select id, user_id, department_id, ip, time_add, url from operation_log where $where order by id desc limit ".$pagination->limitStart.','.$pagination->pageSize;
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$operationLogs = $dbHelper->fetchAll($pdoStatement);

foreach($operationLogs as $key => $operationLog){
    $operationLogs[$key]['time_add_name'] = date('Y-m-d H:i:s', $operationLog['time_add']);
    $operationLogs[$key]['user_name'] = $user->getName($operationLog['user_id']);
    $operationLogs[$key]['department_name'] = $department->getName($operationLog['department_id']);
    $operationLogs[$key]['url_sub'] = MyString::getSubFromZero($operationLog['url'], 60);
}

$search = Safe::entity($search);
$operationLogs = Safe::entity($operationLogs, 'id,url,url_sub');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>操作日志_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/laydate-5.3.1/laydate.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/operation_log/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/operation_log/index.js"></script>
</head>

<body class="page">
<?php require_once '../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once '../../public/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 操作日志
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>操作时间：<span class="time_range"><input type="text" name="time_start" id="time_start" value="<?php echo $search['time_start'];?>" autocomplete="off" /> 到 
<input type="text" name="time_end" id="time_end" value="<?php echo $search['time_end'];?>" autocomplete="off" /></span></li>
<li>ip：<input type="text" name="ip" value="<?php echo $search['ip'];?>" /></li>
<li>部门：<input type="text" name="department_name" value="<?php echo $search['department_name'];?>" /></li>
<li>姓名：<input type="text" name="user_name" value="<?php echo $search['user_name'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun-button" />
</li>
</ul>
</form>
</div>

<div class="data sun-mt10">
<table class="sun-table-list hover" width="100%">
  <tr>
    <th>id</th>
    <th>部门</th>
    <th>用户姓名</th>
    <th>操作ip</th>
    <th>操作url</th>
    <th>操作时间</th>
    <th width="90">操作</th>
  </tr>
<?php
if(!empty($operationLogs)){
foreach($operationLogs as $operationLog){
?>
  <tr>
    <td><?php echo $operationLog['id'];?></td>
    <td><?php echo $operationLog['department_name'];?></td>
    <td><?php echo $operationLog['user_name'];?></td>
    <td><?php echo $operationLog['ip'];?></td>
    <td><?php echo $operationLog['url_sub'];?></td>
    <td><?php echo $operationLog['time_add_name'];?></td>
    <td>
<a href="javascript:;" class="sun-button plain small sun-mr5" onClick="sun.layer.open({id: 'layer_detail', name: '操作日志详情', url: 'detail.php?id=<?php echo $operationLog['id'];?>', width: 700, height: 500})">详情</a>
<a href="../user/detail.php?id=<?php echo $operationLog['user_id'];?>" class="sun-button plain small">用户</a>
    </td>
  </tr>
<?php
}
}else{
?>
<tr>
<td colspan="7" align="center">无数据</td>
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