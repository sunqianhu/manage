<?php
/**
 * 修改头像
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\service\ConfigService;
use library\service\AuthService;

$config = ConfigService::getAll();

// 验证
if(!AuthService::isLogin()){
    header('location:../my/login.php');
    exit;
}

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>修改头像</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link  href="<?php echo $config['app_domain'];?>js/plug/cropperjs-1.5.12/cropper.min.css" rel="stylesheet">
<script src="<?php echo $config['app_domain'];?>js/plug/cropperjs-1.5.12/cropper.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/my/edit_head.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/my/edit_head.js"></script>
</head>

<body class="page">
<div class="page_body">
<div class="cropper">
<div class="img"><img src="../image/user_head.png" /></div>
<div class="tools">
<div class="left">
<label class="sun-button" for="input_image">上传图片</label>
<input type="file" name="avatar" id="input_image" accept="image/*"/>
</div>
<div class="right">
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="reset">重置</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="scaleY" parameter="-1">上下翻转</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="scaleX" parameter="-1">左右翻转</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="rotate" parameter="5">顺时针旋转</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="rotate" parameter="-5">逆时针旋转</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="zoom" parameter="0.1">放大</a>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small button_cropper_control" method="zoom" parameter="-0.1">缩小</a>
</div>
</div>
</div>
<div class="preview">
<div class="img"></div>
</div>
</div>
<div class="page_button">
<a href="javascript:;" class="sun-button sun-button-secondary" onClick="window.parent.sun.layer.close('layer_edit_head');">关闭</a>
<input type="submit" class="sun-button" value="提交" onClick="editHead.submit();" />
</div>
</body>
</html>