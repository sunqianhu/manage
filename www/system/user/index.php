<?php
/**
 * 用户管理
 */
require_once '../../library/app.php';

use library\Auth;
use library\Config;
use library\DbHelper;
use library\FrameMain;
use library\Pagination;
use library\Ztree;
use library\ArrayTwo;
use library\Safe;
use library\model\Dictionary;
use library\model\Department;
use library\model\User;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$config = Config::getAll();
$frameMain = new FrameMain();
$frameMainMenu = ''; // 框架菜单
$wheres = array();
$where = '1';
$recordTotal = 0; // 总记录
$pagination = null; // 分页
$paginationNodeIntact = ''; // 节点
$search = array(
    'id'=>'',
    'department_id'=>'0',
    'department_name'=>'不限',
    'status'=>'0',
    'role_id'=>'0',
    'username'=>'',
    'name'=>'',
    'phone'=>''
); // 搜索
$users = array();
$userModel = new User();
$departments = array();
$department = ''; // 部门json数据
$departmentObject = new Department();
$roles = array();
$optionRole = '';
$optionStatus = '';
$ztree = new Ztree();
$dictionaryModel = new Dictionary();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$frameMainMenu = $frameMain->getMenu('system_user');

if(!empty($_GET['id'])){
    $wheres[] = 'id = :id';
    $data[':id'] = $_GET['id'];
    $search['id'] = $_GET['id'];
}
if(isset($_GET['department_id']) && $_GET['department_id'] != '0'){
    $wheres[] = 'department_id = :department_id';
    $data[':department_id'] = $_GET['department_id'];
    $search['department_id'] = $_GET['department_id'];
}
if(isset($_GET['status']) && $_GET['status'] != '0'){
    $wheres[] = 'status = :status';
    $data[':status'] = $_GET['status'];
    $search['status'] = $_GET['status'];
}
if(isset($_GET['role_id']) && $_GET['role_id'] != '0'){
    $wheres[] = 'find_in_set(:role_id, role_id_string)';
    $data[':role_id'] = $_GET['role_id'];
    $search['role_id'] = $_GET['role_id'];
}
if(isset($_GET['username']) && $_GET['username'] !== ''){
    $wheres[] = 'username like :username';
    $data[':username'] = '%'.$_GET['username'].'%';
    $search['username'] = $_GET['username'];
}
if(isset($_GET['phone']) && $_GET['phone'] !== ''){
    $wheres[] = 'phone like :phone';
    $data[':phone'] = '%'.$_GET['phone'].'%';
    $search['phone'] = $_GET['phone'];
}
if(isset($_GET['name']) && $_GET['name'] !== ''){
    $wheres[] = 'name like :name';
    $data[':name'] = '%'.$_GET['name'].'%';
    $search['name'] = $_GET['name'];
}
if(!empty($wheres)){
    $where = implode(' and ', $wheres);
}

if(isset($_GET['department_name'])){
    $search['department_name'] = $_GET['department_name'];
}

$sql = "select count(1) from user where $where";
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$recordTotal = $dbHelper->fetchColumn($pdoStatement);

$pagination = new Pagination($recordTotal);
$paginationNodeIntact = $pagination->getNodeIntact();

$sql = "select id, username, head, `name`, `login_time`, edit_time, phone, status_id, department_id from user where $where order by id asc limit ".$pagination->limitStart.','.$pagination->pageSize;
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$users = $dbHelper->fetchAll($pdoStatement);
foreach($users as $key => $user){
    $users[$key]['department_name'] = $departmentObject->getName($user['department_id']);
    $users[$key]['status_name'] = $userModel->getBadgeStatusName($user['status_id']);
    $users[$key]['edit_time_name'] = $user['edit_time'] ? date('Y-m-d H:i:s', $user['edit_time']) : '-';
    $users[$key]['login_time_name'] = $user['login_time'] ? date('Y-m-d H:i:s', $user['login_time']) : '-';
    $users[$key]['head_url'] = $userModel->getHeadUrl($user['head']);
}

$sql = 'select id, name, parent_id from department order by parent_id asc, sort asc';
$pdoStatement = $dbHelper->query($pdo, $sql);
$departments = $dbHelper->fetchAll($pdoStatement);

$departments = $ztree->setOpenByFirst($departments);
$department = json_encode($departments);

$optionStatus = $dictionaryModel->getOption('system_user_status', array($search['status']));

$sql = 'select id, name from role order by id asc';
$pdoStatement = $dbHelper->query($pdo, $sql);
$roles = $dbHelper->fetchAll($pdoStatement);
$optionRole = ArrayTwo::getOption($roles, array($search['role_id']), 'id', 'name');

$users = Safe::entity($users, 'status_name');
$search = Safe::entity($search);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/index.js"></script>
<script type="text/javascript">
var departmentData = <?php echo $department;?>;
</script>
</head>

<body class="page">
<?php require_once '../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once '../../public/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span> 用户管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>部门：
<span class="sun-dropdown department">
<div class="title">
<input type="hidden" name="department_id" id="department_id" value="<?php echo $search['department_id'];?>" />
<input type="text" name="department_name" id="department_name" value="<?php echo $search['department_name'];?>" readonly />
</div>
<div class="content">
<ul id="ztree" class="ztree"></ul>
</div>
</span></li>
<li>状态：<select name="status">
<option value="0">不限</option>
<?php echo $optionStatus;?>
</select></li>
<li>角色：<select name="role_id">
<option value="0">不限</option>
<?php echo $optionRole;?>
</select></li>
<li>用户id：<input type="text" name="id" value="<?php echo $search['id'];?>" /></li>
<li>用户名：<input type="text" name="username" value="<?php echo $search['username'];?>" /></li>
<li>用户姓名：<input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>手机号码：<input type="text" name="phone" value="<?php echo $search['phone'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun-button" />
</li>
</ul>
</form>
</div>

<div class="data sun-mt10">
<div class="toolbar">
<a href="javascript:;" class="sun-button" onClick="add(0);">添加</a>
</div>
<table class="sun-table-list hover sun-mt10" width="100%">
  <tr>
    <th>id</th>
    <th>用户名</th>
    <th>姓名</th>
    <th>手机</th>
    <th>部门</th>
    <th>最后修改时间</th>
    <th>最后登录时间</th>
    <th>状态</th>
    <th width="150">操作</th>
  </tr>
<?php
if(!empty($users)){
foreach($users as $user){
?>
  <tr>
    <td><?php echo $user['id'];?></td>
    <td>
<img src="<?php echo $user['head_url'];?>" class="head" />
<?php echo $user['username'];?>
    </td>
    <td><?php echo $user['name'];?></td>
    <td><?php echo $user['phone'];?></td>
    <td><?php echo $user['department_name'];?></td>
    <td><?php echo $user['edit_time_name'];?></td>
    <td><?php echo $user['login_time_name'];?></td>
    <td><?php echo $user['status_name'];?></td>
    <td>
<a href="detail.php?id=<?php echo $user['id'];?>" class="sun-button plain small sun-mr5" target="_blank">详情</a>
<a href="javascript:;" class="sun-button plain small sun-mr5" onClick="edit(<?php echo $user['id'];?>)">修改</a>
<span class="sun-dropdown-menu align-right operation_more">
<div class="title"><a href="javascript:;" class="sun-button plain small">更多 <span class="iconfont icon-arrow-down arrow"></span></a></div>
<div class="content">
<ul>
<li><a href="javascript:;" onClick="enable(<?php echo $user['id'];?>)">启用</a></li>
<li><a href="javascript:;" onClick="disable(<?php echo $user['id'];?>)">停用</a></li>
</ul>
</div>
</span>
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
</html>