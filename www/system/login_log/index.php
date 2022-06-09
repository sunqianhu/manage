<?php
/**
 * 登录日志
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\LoginLogModel;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\PaginationService;
use library\service\SafeService;
use library\service\AuthService;

$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$loginLogModel = new LoginLogModel(); // 模型
$search = array(
    'time_start'=>'',
    'time_end'=>'',
    'username'=>'',
    'name'=>''
); // 搜索
$whereMarks = array();
$whereValues = array();
$where = array();
$paginationService = null; // 分页
$recordTotal = 0; // 总记录
$paginationNodeIntact = ''; // 节点
$loginLogs = array();

if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_login_log')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 菜单
$frameMainMenu = FrameMainService::getPageLeftMenu('system_login_log');

// 搜索
if(isset($_GET['time_start']) && $_GET['time_start'] !== ''){
    $whereMarks[] = 'time_login > :time_start';
    $whereValues[':time_start'] = strtotime($_GET['time_start']);
    $search['time_start'] = $_GET['time_start'];
}
if(isset($_GET['time_end']) && $_GET['time_end'] !== ''){
    $whereMarks[] = 'time_login < :time_end';
    $whereValues[':time_end'] = strtotime($_GET['time_end']);
    $search['time_end'] = $_GET['time_end'];
}
if(isset($_GET['username']) && $_GET['username'] !== ''){
    $whereMarks[] = 'username = :username';
    $whereValues[':username'] = $_GET['username'];
    $search['username'] = $_GET['username'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $whereMarks[] = 'name = :name';
    $whereValues[':name'] = $_GET['name'];
    $search['name'] = $_GET['name'];
}
if(!empty($whereMarks)){
    $where['mark'] = implode(' and ', $whereMarks);
}
if(!empty($whereMarks)){
    $where['value'] = $whereValues;
}

$recordTotal = $loginLogModel->selectOne('count(1)', $where);

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

$loginLogs = $loginLogModel->select('id, user_id, username, name, ip, time_login', $where, 'order by id desc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);

foreach($loginLogs as $key => $loginLog){
    $loginLogs[$key]['time_login_name'] = date('Y-m-d H:i:s', $loginLog['time_login']);
}

$search = SafeService::frontDisplay($search);
$loginLogs = SafeService::frontDisplay($loginLogs, array('id'));

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>登录日志_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/laydate-5.3.1/laydate.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/login_log/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/login_log/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 登录日志
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>登录时间：<span class="time_range"><input type="text" name="time_start" id="time_start" value="<?php echo $search['time_start'];?>" autocomplete="off" />到<input type="text" name="time_end" id="time_end" value="<?php echo $search['time_end'];?>" autocomplete="off" /></span></li>
<li>用户名：<input type="text" name="username" value="<?php echo $search['username'];?>" /></li>
<li>用户姓名：<input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
</li>
</ul>
</form>
</div>

<div class="data sun_mt10">
<div class="toolbar">
<a href="javascript:;" class="sun_button" onClick="index.add();">添加</a>
</div>
<table class="sun_table_list sun_table_list_hover sun_mt10" width="100%">
  <tr>
    <th>id</th>
    <th>用户名</th>
    <th>用户姓名</th>
    <th>登录ip</th>
    <th>登录时间</th>
    <th width="80">操作</th>
  </tr>
<?php
if(!empty($loginLogs)){
foreach($loginLogs as $loginLog){
?>
  <tr>
    <td><?php echo $loginLog['id'];?></td>
    <td><?php echo $loginLog['username'];?></td>
    <td><?php echo $loginLog['name'];?></td>
    <td><?php echo $loginLog['ip'];?></td>
    <td><?php echo $loginLog['time_login_name'];?></td>
    <td>
<a href="../user/detail.php?id=<?php echo $loginLog['user_id'];?>" class="sun_button sun_button_secondary sun_button_small">查看用户</a>
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