<?php
/**
 * 详情
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\OperationLogModel;
use library\service\ValidateService;
use library\service\AuthService;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\SafeService;
use library\service\system\UserService;
use library\service\system\DepartmentService;

$operationLogModel = new OperationLogModel(); // 模型
$validateService = new ValidateService();
$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$operationLog = array();

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_operation_log')){
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

$operationLog = $operationLogModel->selectRow('*', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
if(empty($operationLog)){
    header('location:../../error.php?message='.urlencode('没有找到用户'));
    exit;
}
$operationLog['time_add_name'] = date('Y-m-d H:i:s', $operationLog['time_add']);
$operationLog['user_name'] = UserService::getName($operationLog['user_id']);
$operationLog['department_name'] = DepartmentService::getName($operationLog['department_id']);

$operationLog = SafeService::frontDisplay($operationLog, array('id', 'url'));

// 菜单
$frameMainMenu = FrameMainService::getPageLeftMenu('system_user');

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>日志详情_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/operation_log/detail.css" rel="stylesheet" type="text/css" />
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
<a href="index.php">操作日志</a> <span class="split">&gt;</span>
日志详情
</div>
</div>
<div class="body">
<table width="100%" class="sun_table_view">
<tr>
<td class="name" align="right" width="130">部门：</td>
<td><?php echo $operationLog['department_name'];?></td>
</tr>
<tr>
<td class="name" align="right">用户姓名：</td>
<td><?php echo $operationLog['user_name'];?></td>
</tr>
<tr>
<td class="name" align="right">操作url：</td>
<td><?php echo $operationLog['url'];?></td>
</tr>
<tr>
<td class="name" align="right">操作ip：</td>
<td><?php echo $operationLog['ip'];?></td>
</tr>
<tr>
<td class="name" align="right">操作时间：</td>
<td><?php echo $operationLog['time_add_name'];?></td>
</tr>
<tr>
<td class="name" align="right">post内容：</td>
<td><?php echo $operationLog['post'];?></td>
</tr>
<tr>
<td class="name" align="right">响应内容：</td>
<td><?php echo $operationLog['resonse'];?></td>
</tr>
</table>
</div>
</div>
</div>
</body>
</html>