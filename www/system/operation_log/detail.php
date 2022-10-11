<?php
/**
 * 详情
 */
require_once '../../library/app.php';

use library\Session;
use library\Db;
use library\OperationLog;
use library\Validate;
use library\Auth;
use library\Config;
use library\FrameMain;
use library\Safe;
use library\User;
use library\Department;

$validate = new Validate();
$pdo = Db::getInstance();
$pdoStatement = null;
$config = Config::getAll();
$frameMain = new FrameMain();
$frameMainMenu = ''; // 框架菜单
$operationLog = array();
$sql = '';
$data = array();
$department = new Department();
$user = new User();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_operation_log')){
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

$sql = 'select * from operation_log where id = :id';
$data = array(
    ':id'=>$_GET['id']
);
$pdoStatement = Db::query($pdo, $sql, $data);
$operationLog = Db::fetch($pdoStatement);
if(empty($operationLog)){
    header('location:../../error.php?message='.urlencode('没有找到记录'));
    exit;
}
$operationLog['time_add_name'] = date('Y-m-d H:i:s', $operationLog['time_add']);
$operationLog['user_name'] = $user->getName($operationLog['user_id']);
$operationLog['department_name'] = $department->getName($operationLog['department_id']);

$operationLog = Safe::entity($operationLog, 'url');

// 菜单
$frameMainMenu = $frameMain->getMenu('system_operation_log');
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>详情</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/operation_log/detail.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<table width="100%" class="sun-table-view">
<tr>
<td class="name" align="right" width="130">部门</td>
<td><?php echo $operationLog['department_name'];?></td>
</tr>
<tr>
<td class="name" align="right">用户姓名</td>
<td><?php echo $operationLog['user_name'];?></td>
</tr>
<tr>
<td class="name" align="right">操作url</td>
<td><a href="<?php echo $operationLog['url'];?>" target="_blank"><?php echo $operationLog['url'];?></a></td>
</tr>
<tr>
<td class="name" align="right">操作ip</td>
<td><?php echo $operationLog['ip'];?></td>
</tr>
<tr>
<td class="name" align="right">操作时间</td>
<td><?php echo $operationLog['time_add_name'];?></td>
</tr>
<tr>
<td class="name" align="right">User Agent</td>
<td><?php echo $operationLog['user_agent'];?></td>
</tr>
<tr>
<td class="name" align="right">请求内容</td>
<td><?php echo $operationLog['request'];?></td>
</tr>
</table>
</body>
</html>