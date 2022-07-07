<?php
/**
 * 详情
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\UserFileModel;
use library\service\ValidateService;
use library\service\AuthService;
use library\service\ConfigService;
use library\service\SafeService;
use library\service\FileService;
use library\service\UserFileService;
use library\service\system\UserService;
use library\service\system\DepartmentService;
use library\service\system\DictionaryService;

$userFileModel = new UserFileModel(); // 模型
$validateService = new ValidateService();
$config = ConfigService::getAll();
$userFile = array();

// 验证
if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_user_file')){
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

$userFile = $userFileModel->selectRow('*', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
if(empty($userFile)){
    header('location:../../error.php?message='.urlencode('没有找到用户'));
    exit;
}
$userFile['time_add_name'] = date('Y-m-d H:i:s', $userFile['time_add']);
$userFile['user_name'] = UserService::getName($userFile['user_id']);
$userFile['department_name'] = DepartmentService::getName($userFile['department_id']);
$userFile['module_name'] = DictionaryService::getValue('system_user_file_module', $userFile['module_id']);
$userFile['size_name'] = FileService::getByteReadable($userFile['size']);
$userFile['path_url'] = UserFileService::getUrl($userFile['path']);

$userFile = SafeService::frontDisplay($userFile, 'id,url');
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>详情</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user_file/detail.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<table width="100%" class="sun-table-view">
<tr>
<td class="name" align="right" width="130">id：</td>
<td><?php echo $userFile['id'];?></td>
</tr>
<tr>
<td class="name" align="right">部门：</td>
<td><?php echo $userFile['department_name'];?></td>
</tr>
<tr>
<td class="name" align="right">用户姓名：</td>
<td><?php echo $userFile['user_name'];?></td>
</tr>
<tr>
<td class="name" align="right">模块：</td>
<td><?php echo $userFile['module_name'];?></td>
</tr>
<tr>
<td class="name" align="right">文件名：</td>
<td><?php echo $userFile['name'];?></td>
</tr>
<tr>
<td class="name" align="right">文件大小：</td>
<td><?php echo $userFile['size_name'];?></td>
</tr>
<tr>
<td class="name" align="right">文件类型：</td>
<td><?php echo $userFile['type'];?></td>
</tr>
<tr>
<td class="name" align="right">文件路径：</td>
<td><?php echo $userFile['path'];?> &nbsp; <a href="<?php echo $userFile['path_url'];?>" target="_blank">查看</a></td>
</tr>
<tr>
<td class="name" align="right">上传ip：</td>
<td><?php echo $userFile['ip'];?></td>
</tr>
<tr>
<td class="name" align="right">上传时间：</td>
<td><?php echo $userFile['time_add_name'];?></td>
</tr>
</table>
</body>
</html>