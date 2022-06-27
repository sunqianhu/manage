<?php
/**
 * 系统登录
 */
require_once '../library/app.php';

use library\service\ConfigService;

$config = ConfigService::getAll();

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>系统登录</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/animate-4.1.1/animate.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/particleground/jquery.particleground.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/my/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/my/login.js"></script>
</head>

<body class="page">
<div class="bg"></div>
<div class="wrap animate__animated animate__bounceInDown">
<div class="title"><h1><?php echo $config['app_name'];?></h1></div>
<form action="login_handle.php" method="post" class="form">
<div class="row">
<span class="iconfont icon-user icon"></span>
<input type="text" id="username" name="username" placeholder="请输入用户名" required />
</div>
<div class="row">
<span class="iconfont icon-password  icon"></span>
<input type="password" id="password" name="password" placeholder="请输入密码" required />
</div>
<div class="row captcha">
<span class="iconfont icon-auth icon"></span>
<input type="text" id="captcha" name="captcha" placeholder="请输入密码" required />
<img src="captcha.php?mo=<?php echo time();?>" title="点击更新验证码" onClick="index.changeCaptcha();" />
</div>
<div class="button_box">
<input type="submit" class="sun_button sun_button_big sun_button_block" value="登录" />
</div>
</form>
</div>

<div class="explain">
推荐浏览器：Google Chrome 45+，Mozilla　Firefox 40+，Internet Explorer 10+<br>
copyright © 2022 sun all rights reserved.
</div>
</body>
</html>