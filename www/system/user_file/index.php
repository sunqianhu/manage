<?php
/**
 * 用户文件
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\UserFileModel;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\PaginationService;
use library\service\SafeService;
use library\service\AuthService;
use library\service\FileService;
use library\service\StringService;
use library\service\system\UserService;
use library\service\system\DepartmentService;
use library\service\system\DictionaryService;

$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$userFileModel = new UserFileModel();
$search = array(
    'time_start'=>'',
    'time_end'=>'',
    'department_name'=>'',
    'user_name'=>'',
    'name'=>''
); // 搜索
$whereMarks = array();
$whereValues = array();
$where = array();
$paginationService = null; // 分页
$recordTotal = 0; // 总记录
$paginationNodeIntact = ''; // 节点
$userFiles = array();

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_user_file')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 菜单
$frameMainMenu = FrameMainService::getPageLeftMenu('system_user_file');

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
if(isset($_GET['user_name']) && $_GET['user_name'] !== ''){
    $whereMarks[] = 'user_id in (select id from user where name like :user_name)';
    $whereValues[':user_name'] = '%'.$_GET['user_name'].'%';
    $search['user_name'] = $_GET['user_name'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $whereMarks[] = 'name like :name';
    $whereValues[':name'] = '%'.$_GET['name'].'%';
    $search['name'] = $_GET['name'];
}
if(!empty($whereMarks)){
    $where['mark'] = implode(' and ', $whereMarks);
}
if(!empty($whereMarks)){
    $where['value'] = $whereValues;
}

$recordTotal = $userFileModel->selectOne('count(1)', $where);

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

$userFiles = $userFileModel->select('id, department_id, user_id, module_id, name, path, size, type, ip, time_add', $where, 'order by id desc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);

foreach($userFiles as $key => $userFile){
    $userFiles[$key]['time_add_name'] = date('Y-m-d H:i:s', $userFile['time_add']);
    $userFiles[$key]['department_name'] = DepartmentService::getName($userFile['department_id']);
    $userFiles[$key]['size_name'] = FileService::getByteReadable($userFile['size']);
    $userFiles[$key]['user_name'] = UserService::getName($userFile['user_id']);
    $userFiles[$key]['module_name'] = DictionaryService::getValue('system_user_file_module', $userFile['module_id']);
    $userFiles[$key]['name_sub'] = StringService::getSubFromZero($userFile['name'], 25);
}

$search = SafeService::frontDisplay($search);
$userFiles = SafeService::frontDisplay($userFiles, 'id, user_id, module_id');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户文件_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/laydate-5.3.1/laydate.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user_file/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user_file/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../public/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 用户文件
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>上传时间：<span class="time_range"><input type="text" name="time_start" id="time_start" value="<?php echo $search['time_start'];?>" autocomplete="off" /> 到 
<input type="text" name="time_end" id="time_end" value="<?php echo $search['time_end'];?>" autocomplete="off" /></span></li>
<li>部门：<input type="text" name="department_name" value="<?php echo $search['department_name'];?>" /></li>
<li>姓名：<input type="text" name="user_name" value="<?php echo $search['user_name'];?>" /></li>
<li>文件名：<input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun-button" />
</li>
</ul>
</form>
</div>

<div class="data sun-mt10">
<table class="sun-table-list sun-table-list-hover" width="100%">
  <tr>
    <th>id</th>
    <th>部门</th>
    <th>用户姓名</th>
    <th>模块</th>
    <th>文件名</th>
    <th>文件大小</th>
    <th>上传ip</th>
    <th>上传时间</th>
    <th width="90">操作</th>
  </tr>
<?php
if(!empty($userFiles)){
foreach($userFiles as $userFile){
?>
  <tr>
    <td><?php echo $userFile['id'];?></td>
    <td><?php echo $userFile['department_name'];?></td>
    <td><?php echo $userFile['user_name'];?></td>
    <td><?php echo $userFile['module_name'];?></td>
    <td><span title="<?php echo $userFile['name'];?>"><?php echo $userFile['name_sub'];?></span></td>
    <td><?php echo $userFile['size_name'];?></td>
    <td><?php echo $userFile['ip'];?></td>
    <td><?php echo $userFile['time_add_name'];?></td>
    <td>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small sun-mr5" onClick="sun.layer.open({id: 'layer_detail', name: '文件详情', url: 'detail.php?id=<?php echo $userFile['id'];?>', width: 700, height: 500})">详情</a>
<a href="../user/detail.php?id=<?php echo $userFile['user_id'];?>" class="sun-button sun-button-secondary sun-button-small">用户</a>
    </td>
  </tr>
<?php
}
}else{
?>
<tr>
<td colspan="9" align="center">无数据</td>
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