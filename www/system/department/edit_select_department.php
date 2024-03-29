<?php
/**
 * 选择上级部门
 */
require_once '../../main.php';

use library\helper\Auth;
use library\core\Config;
use library\core\Db;
use library\core\Ztree;

$db = new Db();
$pdo = $db->getPdo();
$pdoStatement = null;
$sql = '';
$config = Config::getAll();
$departments = array();
$department = ''; // 部门json数据
$ztree = new Ztree();

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_department')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$sql = 'select id, name, parent_id from department order by parent_id asc, id asc';
$pdoStatement = $db->query($pdo, $sql);
$departments = $db->fetchAll($pdoStatement);
$departments = $ztree->setOpenByFirst($departments);
$department = json_encode($departments);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选择上级部门</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/department/edit_select_department.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/edit_select_department.js"></script>
<script type="text/javascript">
departmentData = <?php echo $department;?>;
</script>
</head>

<body class="page">
<div class="page_body">
<ul id="ztree" class="ztree"></ul>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('edit_select_department');">关闭</a>
<input type="button" class="sun-button" value="确定" onClick="submit();" />
</div>
</body>
</html>