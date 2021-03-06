<?php
/**
 * 选择部门
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\DepartmentModel;
use library\service\ZtreeService;
use library\service\ConfigService;
use library\service\AuthService;

$config = ConfigService::getAll();
$departmentModel = new DepartmentModel();
$departments = array();
$department = ''; // 部门json数据

if(!AuthService::isLogin()){
    header('location:../../my/login.php');
    exit;
}
if(!AuthService::isPermission('system_user')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

$departments = $departmentModel->selectAll('id, name, parent_id', array(), 'parent_id asc, id asc');
$departments = ZtreeService::setOpenByFirst($departments);
$department = json_encode($departments);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选择部门</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/add_select_department.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/add_select_department.js"></script>
<script type="text/javascript">
addSelectDepartment.departmentData = <?php echo $department;?>;
</script>
</head>

<body class="page">
<div class="page_body">
<ul id="ztree" class="ztree"></ul>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button sun-button-secondary" onClick="window.parent.sun.layer.close('layer_add_select_department');">关闭</a>
<input type="button" class="sun-button" value="确定" onClick="addSelectDepartment.submit();" />
</div>
</body>
</html>