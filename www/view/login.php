<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>系统登录</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun_form_submit-1.0.0/sun_form_submit.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/login.js"></script>
</head>

<body class="page">
<div class="wrap">
<div class="title"><h1><?php echo $config['app_name'];?></h1></div>
<form action="../login/login" method="post" class="needs-validation form">
<div class="form_row">
<span class="iconfont icon-user icon"></span>
<input type="text" id="username" name="username" placeholder="请输入用户名" required />
</div>
<div class="form_row">
<span class="iconfont icon-password  icon"></span>
<input type="password" id="password" name="password" placeholder="请输入密码" required />
</div>
<div class="form_row captcha">
<span class="iconfont icon-auth icon"></span>
<input type="text" id="captcha" name="captcha" placeholder="请输入密码" required />
<img src="../login/captcha?mo=<?php echo time();?>" title="点击更新验证码" onClick="changeCaptcha();" />
</div>
<div class="button_box">
<button class="sun_button sun_button_block">登录</button>
</div>
</form>
</div>

<div class="copyright">copyright © 2021 b5net.com all rights reserved.</div>
</body>
</html>