<?php
/**
 * 用户详情
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\UserModel;
use library\model\system\RoleModel;
use library\model\system\DepartmentModel;
use library\model\system\LoginLogModel;
use library\model\system\OperationLogModel;
use library\service\ValidateService;
use library\service\AuthService;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\SafeService;
use library\service\system\DictionaryService;
use library\service\ArrayTwoService;
use library\service\StringService;

$userModel = new UserModel(); // 模型
$departmentModel = new DepartmentModel();
$roleModel = new RoleModel();
$loginLogModel = new LoginLogModel();
$operationLogModel = new OperationLogModel();
$validateService = new ValidateService();
$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$roles = array(); // 角色
$loginLogs = array();
$loginLog = array();
$operationLogs = array(); // 操作日志
$operationLog = array();

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
$validateService->rule = array(
    'id' => 'require|number'
);
$validateService->message = array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
);
if(!$validateService->check($_GET)){
    header('location:../../error.php?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

$user = $userModel->selectRow('id, username, `name`, `phone`, `status`, department_id, role_id_string, time_add, time_login, time_edit, ip', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
if(empty($user)){
    header('location:../../error.php?message='.urlencode('没有找到用户'));
    exit;
}

$user['status_name'] = DictionaryService::getValue('system_user_status', $user['status']);
$user['time_add_name'] = date('Y-m-d H:i:s', $user['time_add']);
$user['time_edit_name'] = $user['time_edit'] ? date('Y-m-d H:i:s', $user['time_edit']) : '-';
$user['time_login_name'] = $user['time_login'] ? date('Y-m-d H:i:s', $user['time_login']) : '-';
$user['department_name'] = $departmentModel->selectOne('name', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$user['department_id']
    )
));

$roles = $roleModel->select('name', array(
    'mark'=>'id in (:id)',
    'value'=>array(
        ':id'=>$user['role_id_string']
    )
));
$user['role_name'] = ArrayTwoService::getColumnString($roles, 'name', '，');
$user = SafeService::frontDisplay($user, array('id'));

// 登录日志
$loginLogs = $loginLogModel->select("ip, time_login",  array(
    'mark'=>'user_id = :user_id',
    'value'=>array(
        ':user_id'=>$user['id']
    )
), 'order by id desc', 'limit 0,50');
$loginLogs = ArrayTwoService::columnTimestampToTime($loginLogs, 'time_login', 'time_login_name');

// 操作日志
$operationLogs = $operationLogModel->select("id, ip, time_add, url",  array(
    'mark'=>'user_id = :user_id',
    'value'=>array(
        ':user_id'=>$user['id']
    )
), 'order by id desc', 'limit 0,50');
$operationLogs = ArrayTwoService::columnTimestampToTime($operationLogs, 'time_add', 'time_add_name');

foreach($operationLogs as $key => $operationLog){
    $operationLogs[$key]['url_sub'] = StringService::subStart($operationLog['url'], 60);
}

// 菜单
$frameMainMenu = FrameMainService::getPageLeftMenu('system_user');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户详情_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/detail.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.php">系统首页</a> <span class="split">&gt;</span>
<a href="index.php">用户管理</a> <span class="split">&gt;</span>
用户详情
</div>
</div>
<div class="body">

<div class="sun_section">
<div class="title">
<span class="name">用户信息</span>
</div>
<div class="content">
<table width="100%" class="sun_table_view">
<tr>
<td class="name" align="right" width="130">用户id：</td>
<td><?php echo $user['id'];?></td>
<td class="name" align="right" width="130">部门：</td>
<td><?php echo $user['department_name'];?></td>
<td class="name" align="right" width="130">状态：</td>
<td><?php echo $user['status_name'];?></td>
</tr>
<tr>
<td class="name" align="right">用户名：</td>
<td><?php echo $user['username'];?></td>
<td class="name" align="right">姓名：</td>
<td><?php echo $user['name'];?></td>
<td class="name" align="right">手机号码：</td>
<td><?php echo $user['phone'];?></td>
</tr>
<tr>
<td class="name" align="right">添加时间：</td>
<td><?php echo $user['time_add_name'];?></td>
<td class="name" align="right">最后修改时间：</td>
<td><?php echo $user['time_edit_name'];?></td>
<td class="name" align="right">最后登录时间：</td>
<td><?php echo $user['time_login_name'];?></td>
</tr>
<tr>
<td class="name" align="right">登录ip：</td>
<td><?php echo $user['ip'];?></td>
<td class="name" align="right">角色：</td>
<td colspan="3"><?php echo $user['role_name'];?></td>
</tr>
</table>
</div>
</div>

<div class="sun_section sun_mt10">
<div class="title">
<span class="name">登录日志</span>
<span class="describe">显示最后50条</span>
<a href="../login_log/index.php?user_id=<?php echo $user['id'];?>" class="more" target="_blank">更多</a>
</div>
<div class="content">

<table width="100%" class="sun_table_view">
<tr>
<td class="name">登录ip</td>
<td class="name">登录时间</td>
</tr>
<?php
if(!empty($loginLogs)){
foreach($loginLogs as $loginLog){
?>
<tr>
<td><?php echo $loginLog['ip'];?></td>
<td><?php echo $loginLog['time_login_name'];?></td>
</tr>
<?php
}
}else{
?>
<tr>
<td colspan="2" align="center">无</td>
</tr>
<?php
}
?>
</table>

</div>
</div>

<div class="sun_section sun_mt10">
<div class="title">
<span class="name">操作日志</span>
<span class="describe">显示最后50条</span>
<a href="../operation_log/index.php?user_id=<?php echo $user['id'];?>" class="more" target="_blank">更多</a>
</div>
<div class="content">

<table width="100%" class="sun_table_view">
<tr>
<td class="name">操作ip</td>
<td class="name">操作时间</td>
<td class="name">操作url</td>
<td class="name" width="50">详情</td>
</tr>
<?php
if(!empty($operationLogs)){
foreach($operationLogs as $operationLog){
?>
<tr>
<td><?php echo $operationLog['ip'];?></td>
<td><?php echo $operationLog['time_add_name'];?></td>
<td><a href="<?php echo $operationLog['url'];?>" target="_blank"><?php echo $operationLog['url_sub'];?></a></td>
<td><a href="../operation_log/detail.php?id=<?php echo $operationLog['id'];?>" target="_blank">详情</a></td>
</tr>
<?php
}
}else{
?>
<tr>
<td colspan="3" align="center">无</td>
</tr>
<?php
}
?>
</table>

</div>
</div>

</div>
</div>
</div>
</body>
</html>