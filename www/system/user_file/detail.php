<?php
/**
 * 详情
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\Db;
use library\Validate;
use library\Auth;
use library\Config;
use library\Safe;
use library\File;
use library\UserFile;
use library\User;
use library\Department;
use library\Dictionary;

$userFileModel = new UserFileModel(); // 模型
$config = Config::getAll();
$userFile = array();

// 验证
if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_user_file')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
Validate::setRule(array(
    'id' => 'require|number'
);
Validate::setMessage(array(
    'id.require' => 'id参数错误',
    'id.number' => 'id必须是个数字'
);
if(!Validate::check($_GET)){
    header('location:../../error.php?message='.urlencode(Validate::getErrorMessage()));
    exit;
}

$userFile = Db::selectRow('*', array(
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
$userFile['user_name'] = User::getName($userFile['user_id']);
$userFile['department_name'] = Department::getName($userFile['department_id']);
$userFile['module_name'] = Dictionary::getValue('system_user_file_module', $userFile['module_id']);
$userFile['size_name'] = File::getByteReadable($userFile['size']);
$userFile['path_url'] = UserFile::getUrl($userFile['path']);

$userFile = Safe::frontDisplay($userFile, 'id,url');
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>详情</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user_file/detail.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<table width="100%" class="sun-table-view">
<tr>
<td class="name" align="right" width="130">id</td>
<td><?php echo $userFile['id'];?></td>
</tr>
<tr>
<td class="name" align="right">部门</td>
<td><?php echo $userFile['department_name'];?></td>
</tr>
<tr>
<td class="name" align="right">用户姓名</td>
<td><?php echo $userFile['user_name'];?></td>
</tr>
<tr>
<td class="name" align="right">模块</td>
<td><?php echo $userFile['module_name'];?></td>
</tr>
<tr>
<td class="name" align="right">文件名</td>
<td><?php echo $userFile['name'];?></td>
</tr>
<tr>
<td class="name" align="right">文件大小</td>
<td><?php echo $userFile['size_name'];?></td>
</tr>
<tr>
<td class="name" align="right">文件类型</td>
<td><?php echo $userFile['type'];?></td>
</tr>
<tr>
<td class="name" align="right">文件路径</td>
<td><?php echo $userFile['path'];?> &nbsp; <a href="<?php echo $userFile['path_url'];?>" target="_blank">查看</a></td>
</tr>
<tr>
<td class="name" align="right">上传ip</td>
<td><?php echo $userFile['ip'];?></td>
</tr>
<tr>
<td class="name" align="right">上传时间</td>
<td><?php echo $userFile['time_add_name'];?></td>
</tr>
</table>
</body>
</html>