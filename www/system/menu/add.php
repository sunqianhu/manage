<?php
/**
 * 添加
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\MenuModel;
use library\service\ConfigService;
use library\service\system\DictionaryService;
use library\service\AuthService;
use library\service\ValidateService;
use library\service\SafeService;

$menuModel = new MenuModel();
$config = ConfigService::getAll();
$validateService = new ValidateService();
$menuTypeRadioNode = DictionaryService::getRadio('system_menu_type', 'type', 1);
$menuParent = array();
$init = array(
    'parent_id'=>1,
    'parent_name'=>'顶级菜单',
);

if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_menu')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}
$validateService->rule = array(
    'parent_id' => 'number'
);
$validateService->message = array(
    'parent_id.number' => 'parent_id必须是个数字'
);
if(!$validateService->check($_GET)){
    header('location:../../error.php?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

if(!empty($_GET['parent_id'])){
    $menuParent = $menuModel->selectRow('id, name', array(
        'mark'=>'id = :id',
        'value'=>array(
            ':id'=> $_GET['parent_id']
        )
    ));
    if(!empty($menuParent)){
        $init['parent_id'] = $menuParent['id'];
        $init['parent_name'] = $menuParent['name'];
    }
    $init = SafeService::frontDisplay($init);
}

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>添加菜单</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/menu/add.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/menu/add.js"></script>
</head>

<body class="page">
<form method="post" action="add_save.php" class="sun_form_brief form">
<div class="page_body">
<div class="row">
<div class="title"><span class="required">*</span> 上级菜单</div>
<div class="content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $init['parent_id'];?>" />
<div class="sun_input_group" onClick="add.selectMenu();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $init['parent_name'];?>" />
<span class="addon"><span class="iconfont icon-magnifier icon"></span></span>
</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 菜单类型</div>
<div class="content">
<?php echo $menuTypeRadioNode;?>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 菜单名称</div>
<div class="content">
<input type="text" name="name" id="name" />
</div>
</div>

<div class="row">
<div class="title">菜单标识</div>
<div class="content">
<input type="text" name="tag" id="tag" />
</div>
</div>

<div class="row">
<div class="title">图标</div>
<div class="content">
<input type="text" name="icon_class" id="icon_class" />
</div>
</div>

<div class="row">
<div class="title">导航URL</div>
<div class="content">
<input type="text" name="url" id="url" style="width: 300px" />
</div>
</div>

<div class="row">
<div class="title">权限标识</div>
<div class="content">
<input type="text" name="permission" id="permission" class="permission" />
<div class="tip">例如：“system_user_add”，从根开始写。</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 排序</div>
<div class="content">
<input type="number" name="sort" id="sort" value="1" />
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_menu_add');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>