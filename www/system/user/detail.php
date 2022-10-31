<?php
/**
 * 用户详情
 */
require_once '../../library/app.php';

use library\Auth;
use library\DbHelper;
use library\Validate;
use library\Config;
use library\Safe;
use library\ArrayTwo;
use library\MyString;
use library\model\Dictionary;
use library\model\Department;

$dbHelper = new DbHelper();
$pdo = $dbHelper->getPdo();
$pdoStatement = null;
$sql = '';
$data = array();
$validate = new Validate();
$config = Config::getAll();
$roles = array(); // 角色
$loginLogs = array();
$loginLog = array();
$operationLogs = array(); // 操作日志
$operationLog = array();
$departmentModel = new Department();
$dictionaryModel = new Dictionary();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
$validate->setRule(array(
    'id' => 'require|number'
));
$validate->setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
));
if(!$validate->check($_GET)){
    header('location:../../error.php?message='.urlencode($validate->getErrorMessage()));
    exit;
}

$sql = 'select id, username, name, phone, status_id, department_id, role_id_string, add_time, login_time, edit_time, ip from user where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$user = $dbHelper->fetch($pdoStatement);
if(empty($user)){
    header('location:../../error.php?message='.urlencode('没有找到用户'));
    exit;
}

$user['status_name'] = $dictionaryModel->getValue('system_user_status', $user['status_id']);
$user['add_time_name'] = date('Y-m-d H:i:s', $user['add_time']);
$user['edit_time_name'] = $user['edit_time'] ? date('Y-m-d H:i:s', $user['edit_time']) : '-';
$user['login_time_name'] = $user['login_time'] ? date('Y-m-d H:i:s', $user['login_time']) : '-';
$user['department_name'] = $departmentModel->getName($user['department_id']);

$sql = 'select name from role where id in (:id)';
$data = array(
    ':id'=>$user['role_id_string']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$roles = $dbHelper->fetchAll($pdoStatement);
$user['role_name'] = ArrayTwo::getColumnString($roles, 'name', '，');
$user = Safe::entity($user);

// 登录日志
$sql = "select ip, login_time from login_log where user_id = :user_id order by id desc limit 0,10";
$data = array(
    ':user_id'=>$user['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$loginLogs = $dbHelper->fetchAll($pdoStatement);
$loginLogs = ArrayTwo::columnTimestampToTime($loginLogs, 'login_time', 'login_time_name');
$loginLogs = Safe::entity($loginLogs);

// 操作日志
$sql = "select id, ip, add_time, url from operation_log where user_id = :user_id order by id desc limit 0,10";
$data = array(
    ':user_id'=>$user['id']
);
$pdoStatement = $dbHelper->query($pdo, $sql, $data);
$operationLogs = $dbHelper->fetchAll($pdoStatement);
$operationLogs = ArrayTwo::columnTimestampToTime($operationLogs, 'add_time', 'add_time_name');
foreach($operationLogs as $key => $operationLog){
    $operationLogs[$key]['url_sub'] = MyString::getSubFromZero($operationLog['url'], 60);
}
$operationLogs = Safe::entity($operationLogs, 'url, url_sub');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户详情_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/detail.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<div class="name"><h1><?php echo $user['name'];?></h1></div>
<div class="sun-section sun-mt10">
<div class="title">
<span class="name">用户信息</span>
</div>
<div class="content">
<table width="100%" class="sun-table-view">
<tr>
<td class="name" align="right" width="130">用户id</td>
<td><?php echo $user['id'];?></td>
<td class="name" align="right" width="130">部门</td>
<td><?php echo $user['department_name'];?></td>
<td class="name" align="right" width="130">状态</td>
<td><?php echo $user['status_name'];?></td>
</tr>
<tr>
<td class="name" align="right">用户名</td>
<td><?php echo $user['username'];?></td>
<td class="name" align="right">姓名</td>
<td><?php echo $user['name'];?></td>
<td class="name" align="right">手机号码</td>
<td><?php echo $user['phone'];?></td>
</tr>
<tr>
<td class="name" align="right">添加时间</td>
<td><?php echo $user['add_time_name'];?></td>
<td class="name" align="right">最后修改时间</td>
<td><?php echo $user['edit_time_name'];?></td>
<td class="name" align="right">最后登录时间</td>
<td><?php echo $user['login_time_name'];?></td>
</tr>
<tr>
<td class="name" align="right">登录ip</td>
<td><?php echo $user['ip'];?></td>
<td class="name" align="right">角色</td>
<td colspan="3"><?php echo $user['role_name'];?></td>
</tr>
</table>
</div>
</div>

<div class="sun-section sun-mt10">
<div class="title">
<span class="name">登录日志</span>
<span class="describe">显示最后10条</span>
<a href="../login_log/index.php?user_id=<?php echo $user['id'];?>" class="more" target="_blank">更多</a>
</div>
<div class="content">

<table width="100%" class="sun-table-view">
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
<td><?php echo $loginLog['login_time_name'];?></td>
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

<div class="sun-section sun-mt10">
<div class="title">
<span class="name">操作日志</span>
<span class="describe">显示最后10条</span>
<a href="../operation_log/index.php?user_id=<?php echo $user['id'];?>" class="more" target="_blank">更多</a>
</div>
<div class="content">

<table width="100%" class="sun-table-view">
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
<td><?php echo $operationLog['add_time_name'];?></td>
<td><?php echo $operationLog['url_sub'];?></td>
<td><a href="../operation_log/detail.php?id=<?php echo $operationLog['id'];?>" target="_blank">详情</a></td>
</tr>
<?php
}
}else{
?>
<tr>
<td colspan="4" align="center">无</td>
</tr>
<?php
}
?>
</table>

</div>
</div>

</body>
</html>