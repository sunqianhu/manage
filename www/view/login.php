<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>登录</title>
<link href="<?php echo $config['site_domain'];?>css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/sun_form_submit-1.0.0/sun_form_submit.js"></script>
<link href="<?php echo $config['site_domain'];?>js/plug/sun_toast-1.0.0/sun_toast.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/sun_toast-1.0.0/sun_toast.js"></script>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/login.js"></script>
</head>

<body class="page_login">
<div class="wrap">
<div class="title"><h1><?php echo $config['site_name'];?></h1></div>
<form action="index.php?c=login&a=login&response=json" method="post" class="form">
<div class="form_group">
    <span class="iconfont icon-user icon"></span>
    <input type="text" id="username" name="username" value="" placeholder="请输入用户名">
</div>
<div class="form_group">
    <span class="iconfont icon-password icon"></span>
    <input type="password" id="password" name="password" value="" placeholder="请输入密码">
</div>
<div class="form_group captcha">
    <span class="iconfont icon-auth icon"></span>
    <input type="password" id="password" name="password" value="" placeholder="请输入密码">
    <img src="<?php echo $config['site_domain'];?>index.php?c=login&a=captcha&mo=<?php echo time();?>" title="点击更新验证码" onClick="changeCaptcha();" />
</div>
<div class="button_box">
    <button class="button button_block">登录</button>
</div>
</form>
</div>

<div class="copyright">copyright © 2021 b5net.com all rights reserved.</div>

</body>
</html>