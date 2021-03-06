<?php
/**
 * 用户管理
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\UserModel;
use library\model\system\DepartmentModel;
use library\model\system\RoleModel;
use library\service\AuthService;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\PaginationService;
use library\service\ZtreeService;
use library\service\ArrayTwoService;
use library\service\SafeService;
use library\service\system\DictionaryService;
use library\service\system\UserService;

$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$userModel = new UserModel(); // 模型
$departmentModel = new DepartmentModel();
$roleModel = new RoleModel();

$whereMarks = array();
$whereValues = array();
$where = array();

$paginationService = null; // 分页
$recordTotal = 0; // 总记录
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
$departments = array();
$department = ''; // 部门json数据
$roles = array();
$roleOption = '';
$statusOption = '';

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$frameMainMenu = FrameMainService::getPageLeftMenu('system_user');

if(!empty($_GET['id'])){
    $whereMarks[] = 'id = :id';
    $whereValues[':id'] = $_GET['id'];
    $search['id'] = $_GET['id'];
}
if(isset($_GET['department_id']) && $_GET['department_id'] != '0'){
    $whereMarks[] = 'department_id = :department_id';
    $whereValues[':department_id'] = $_GET['department_id'];
    $search['department_id'] = $_GET['department_id'];
}
if(isset($_GET['status']) && $_GET['status'] != '0'){
    $whereMarks[] = 'status = :status';
    $whereValues[':status'] = $_GET['status'];
    $search['status'] = $_GET['status'];
}
if(isset($_GET['role_id']) && $_GET['role_id'] != '0'){
    $whereMarks[] = 'find_in_set(:role_id, role_id_string)';
    $whereValues[':role_id'] = $_GET['role_id'];
    $search['role_id'] = $_GET['role_id'];
}
if(isset($_GET['username']) && $_GET['username'] !== ''){
    $whereMarks[] = 'username like :username';
    $whereValues[':username'] = '%'.$_GET['username'].'%';
    $search['username'] = $_GET['username'];
}
if(isset($_GET['phone']) && $_GET['phone'] !== ''){
    $whereMarks[] = 'phone like :phone';
    $whereValues[':phone'] = '%'.$_GET['phone'].'%';
    $search['phone'] = $_GET['phone'];
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

if(isset($_GET['department_name'])){
    $search['department_name'] = $_GET['department_name'];
}

$recordTotal = $userModel->selectOne('count(1)', $where);

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

$users = $userModel->selectAll('id, username, head, `name`, `time_login`, time_edit, phone, status, department_id', $where, 'id asc', ''.$paginationService->limitStart.','.$paginationService->pageSize);
foreach($users as $key => $user){
    $users[$key]['department_name'] = $departmentModel->selectOne('name', array(
        'mark'=>'id = :id',
        'value'=>array(
            ':id'=>$user['department_id']
        )
    ));
    $users[$key]['status_name'] = DictionaryService::getValue('system_user_status', $user['status']);
    $users[$key]['status_style_class'] = $user['status'] == 2 ? 'sun-badge sun-badge-orange': 'sun-badge';
    $users[$key]['time_edit_name'] = $user['time_edit'] ? date('Y-m-d H:i:s', $user['time_edit']) : '-';
    $users[$key]['time_login_name'] = $user['time_login'] ? date('Y-m-d H:i:s', $user['time_login']) : '-';
    $users[$key]['head_url'] = UserService::getHeadUrl($user['head']);
}

$departments = $departmentModel->selectAll('id, name, parent_id', array(), 'parent_id asc, sort asc');
$departments = ZtreeService::setOpenByFirst($departments);
$department = json_encode($departments);

$statusOption = DictionaryService::getSelectOption('system_user_status', array($search['status']));
$roles = $roleModel->selectAll('id, name', array());
$roleOption = ArrayTwoService::getSelectOption($roles, array($search['role_id']), 'id', 'name');

$users = SafeService::frontDisplay($users);
$search = SafeService::frontDisplay($search);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/index.js"></script>
<script type="text/javascript">
index.departmentData = <?php echo $department;?>;
</script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../public/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../public/frame_main_left.php';?>
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
<?php echo $statusOption;?>
</select></li>
<li>角色：<select name="role_id">
<option value="0">不限</option>
<?php echo $roleOption;?>
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
<a href="javascript:;" class="sun-button" onClick="index.add(0);">添加</a>
</div>
<table class="sun-table-list sun-table-list-hover sun-mt10" width="100%">
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
    <td><?php echo $user['time_edit_name'];?></td>
    <td><?php echo $user['time_login_name'];?></td>
    <td><span class="<?php echo $user['status_style_class'];?>"><?php echo $user['status_name'];?></span></td>
    <td>
<a href="detail.php?id=<?php echo $user['id'];?>" class="sun-button sun-button-secondary sun-button-small sun-mr5">详情</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small sun-mr5" onClick="index.edit(<?php echo $user['id'];?>)">修改</a>
<span class="sun-dropdown-menu sun-dropdown-menu-align-right operation_more">
<div class="title"><a href="javascript:;" class="sun-button sun-button-secondary sun-button-small">更多 <span class="iconfont icon-arrow-down arrow"></span></a></div>
<div class="content">
<ul>
<li><a href="javascript:;" onClick="index.enable(<?php echo $user['id'];?>)">启用</a></li>
<li><a href="javascript:;" onClick="index.disable(<?php echo $user['id'];?>)">停用</a></li>
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