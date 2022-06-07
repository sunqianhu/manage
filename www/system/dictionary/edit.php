<?php
/**
 * 修改
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\DictionaryModel;
use library\service\ConfigService;
use library\service\ValidateService;
use library\service\SafeService;
use library\service\AuthService;

$config = ConfigService::getAll();
$validateService = new ValidateService();
$dictionaryModel = new DictionaryModel();
$dictionary = array();

// 验证
if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_dictionary')){
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
    header('location:../../error.html?message='.urlencode($validateService->getErrorMessage()));
    exit;
}

$dictionary = $dictionaryModel->selectRow('id, type, `key`, `value`, `sort`', array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$_GET['id']
    )
));
$dictionary = SafeService::frontDisplay($dictionary, array('id'));

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改字典</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/dictionary/edit.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/dictionary/edit.js"></script>
</head>

<body class="page">
<form method="post" action="edit_save.php" class="sun_form form">
<div class="page_body">
<input type="hidden" name="id" value="<?php echo $dictionary['id'];?>" />
<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 字典类型</div>
<div class="sun_form_content">
<input type="text" name="type" id="type" value="<?php echo $dictionary['type'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 字典键</div>
<div class="sun_form_content">
<input type="text" name="key" id="key" value="<?php echo $dictionary['key'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 字典值</div>
<div class="sun_form_content">
<input type="text" name="value" id="value" value="<?php echo $dictionary['value'];?>" />
</div>
</div>

<div class="sun_form_item">
<div class="sun_form_label"><span class="sun_form_required">*</span> 排序</div>
<div class="sun_form_content">
<input type="number" name="sort" id="sort" value="<?php echo $dictionary['sort'];?>" />
</div>
</div>

</div>
<div class="page_button">
<a href="javascript:;" class="sun_button sun_button_secondary" onClick="window.parent.sun.layer.close('layer_dictionary_edit');">关闭</a>
<input type="submit" class="sun_button" value="提交" />
</div>
</form>
</body>
</html>