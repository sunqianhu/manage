<?php
/**
 * 修改
 */
require_once '../../library/session.php';
require_once '../../library/app.php';

use library\model\system\MenuModel;
use library\service\ConfigService;
use library\service\ValidateService;
use library\service\SafeService;
use library\service\system\DictionaryService;
use library\service\AuthService;

$config = ConfigService::getAll();
$validateService = new ValidateService();
$menuModel = new MenuModel();
$menu = array();

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_menu')){
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

$menu = $menuModel->selectRow('id, parent_id, type, name, `permission`, `sort`, icon_class, url, tag', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
$menu['parent_name'] = $menuModel->selectOne('name', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=> $menu['parent_id']
    )
));
$menu = SafeService::frontDisplay($menu);

$menuTypeRadioNode = DictionaryService::getRadio('system_menu_type', 'type', $menu['type']);

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改菜单</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/menu/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/menu/edit.js"></script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun_form_brief form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $menu['id'];?>" />
<div class="row">
<div class="title"><span class="required">*</span> 上级菜单</div>
<div class="content">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $menu['parent_id'];?>" />
<div class="sun_input_group" onClick="edit.selectMenu();">
<input type="text" name="parent_name" id="parent_name" readonly value="<?php echo $menu['parent_name'];?>" />
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
<input type="text" name="name" id="name" value="<?php echo $menu['name'];?>" />
</div>
</div>

<div class="row">
<div class="title">菜单标识</div>
<div class="content">
<input type="text" name="tag" id="tag" value="<?php echo $menu['tag'];?>" />
</div>
</div>

<div class="row">
<div class="title">图标</div>
<div class="content">
<input type="text" name="icon_class" id="icon_class" value="<?php echo $menu['icon_class'];?>" />
</div>
</div>

<div class="row">
<div class="title">导航URL</div>
<div class="content">
<input type="text" name="url" id="url" style="width: 300px" value="<?php echo $menu['url'];?>" />
</div>
</div>

<div class="row">
<div class="title">权限标识</div>
<div class="content">
<input type="text" name="permission" id="permission" class="permission" value="<?php echo $menu['permission'];?>" />
<div class="tip">例如：“system_user_add”，从根开始写。</div>
</div>
</div>

<div class="row">
<div class="title"><span class="required">*</span> 排序</div>
<div class="content">
<input type="number" name="sort" id="sort" value="<?php echo $menu['sort'];?>" />
</div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_menu_edit');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>