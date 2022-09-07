<?php
/**
 * 选择上级部门
 */
require_once '../../library/app.php';

use library\Db;
use library\Config;
use library\Ztree;
use library\Auth;

$config = Config::getAll();
$departmentModel = new DepartmentModel();
$departments = array();
$department = ''; // 部门json数据

if(!Auth::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!Auth::isPermission('system_department')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$departments = Db::selectAll('id, name, parent_id', array(), 'parent_id asc, id asc');
$departments = Ztree::setOpenByFirst($departments);
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
editSelectDepartment.departmentData = <?php echo $department;?>;
</script>
</head>

<body class="page">
<div class="page_body">
<ul id="ztree" class="ztree"></ul>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button plain" onClick="window.parent.sun.layer.close('layer_edit_select_department');">关闭</a>
<input type="button" class="sun-button" value="确定" onClick="editSelectDepartment.submit();" />
</div>
</body>
</html>