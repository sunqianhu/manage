<?php
/**
 * 操作日志
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\OperationLogModel;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\PaginationService;
use library\service\SafeService;
use library\service\AuthService;
use library\service\StringService;
use library\service\system\UserService;
use library\service\system\DepartmentService;

$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$operationLogModel = new OperationLogModel(); // 模型
$search = array(
    'time_start'=>'',
    'time_end'=>'',
    'department_name'=>'',
    'user_name'=>''
); // 搜索
$whereMarks = array();
$whereValues = array();
$where = array();
$paginationService = null; // 分页
$recordTotal = 0; // 总记录
$paginationNodeIntact = ''; // 节点
$operationLogs = array();

if(!AuthService::isLogin()){
    header('location:../../operation/index.php');
    exit;
}
if(!AuthService::isPermission('system_operation_log')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 菜单
$frameMainMenu = FrameMainService::getPageLeftMenu('system_operation_log');

// 搜索
if(isset($_GET['time_start']) && $_GET['time_start'] !== ''){
    $whereMarks[] = 'time_add > :time_start';
    $whereValues[':time_start'] = strtotime($_GET['time_start']);
    $search['time_start'] = $_GET['time_start'];
}
if(isset($_GET['time_end']) && $_GET['time_end'] !== ''){
    $whereMarks[] = 'time_add < :time_end';
    $whereValues[':time_end'] = strtotime($_GET['time_end']);
    $search['time_end'] = $_GET['time_end'];
}
if(isset($_GET['department_name']) && $_GET['department_name'] !== ''){
    $whereMarks[] = 'department_id in (select id from department where name like :department_name)';
    $whereValues[':department_name'] = '%'.$_GET['department_name'].'%';
    $search['department_name'] = $_GET['department_name'];
}
if(isset($_GET['user_id']) && $_GET['user_id'] !== ''){
    $whereMarks[] = 'user_id = :user_id';
    $whereValues[':user_id'] = $_GET['user_id'];
}
if(isset($_GET['user_name']) && $_GET['user_name'] !== ''){
    $whereMarks[] = 'user_id in (select id from user where name like :user_name)';
    $whereValues[':user_name'] = '%'.$_GET['user_name'].'%';
    $search['user_name'] = $_GET['user_name'];
}
if(!empty($whereMarks)){
    $where['mark'] = implode(' and ', $whereMarks);
}
if(!empty($whereMarks)){
    $where['value'] = $whereValues;
}

$recordTotal = $operationLogModel->selectOne('count(1)', $where);

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

$operationLogs = $operationLogModel->select('id, user_id, department_id, ip, time_add, url', $where, 'order by id desc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);

foreach($operationLogs as $key => $operationLog){
    $operationLogs[$key]['time_add_name'] = date('Y-m-d H:i:s', $operationLog['time_add']);
    $operationLogs[$key]['user_name'] = UserService::getName($operationLog['user_id']);
    $operationLogs[$key]['department_name'] = DepartmentService::getName($operationLog['department_id']);
    $operationLogs[$key]['url_sub'] = StringService::getSubFromZero($operationLog['url'], 60);
}

$search = SafeService::frontDisplay($search);
$operationLogs = SafeService::frontDisplay($operationLogs, 'id,url,url_sub');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>操作日志_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/laydate-5.3.1/laydate.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/operation_log/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/operation_log/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../public/frame_main_left.php';?>
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
<li>部门：<input type="text" name="department_name" value="<?php echo $search['department_name'];?>" /></li>
<li>姓名：<input type="text" name="user_name" value="<?php echo $search['user_name'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
</li>
</ul>
</form>
</div>

<div class="data sun_mt10">
<table class="sun_table_list sun_table_list_hover" width="100%">
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
    <td><a href="<?php echo $operationLog['url'];?>" target="_blank" title="<?php echo $operationLog['url'];?>"><?php echo $operationLog['url_sub'];?></a></td>
    <td><?php echo $operationLog['time_add_name'];?></td>
    <td>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small sun_mr5" onClick="sun.layer.open({id: 'layer_detail', name: '操作日志详情', url: 'detail.php?id=<?php echo $operationLog['id'];?>', width: 700, height: 500})">详情</a>
<a href="../user/detail.php?id=<?php echo $operationLog['user_id'];?>" class="sun_button sun_button_secondary sun_button_small">用户</a>
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