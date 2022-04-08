<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>系统登录</title>
<link href="<?php echo $config['site_domain'];?>js/plug/bootstrap-5.1.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
<link href="<?php echo $config['site_domain'];?>css/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config['site_domain'];?>css/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config['site_domain'];?>css/login/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/sun_form_submit-1.0.0/sun_form_submit.js"></script>
<link href="<?php echo $config['site_domain'];?>js/plug/sun_toast-1.0.0/sun_toast.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/sun_toast-1.0.0/sun_toast.js"></script>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/login/index.js"></script>
</head>

<body class="body">
<div class="wrap">
<div class="title"><h1><?php echo $config['site_name'];?></h1></div>
<form action="login.php" method="post" class="needs-validation form">
<div class="form_group">
    <span class="iconfont icon-user icon"></span>
    <input type="text" id="userid" name="userid" class="form-control" placeholder="请输入用户名" required />
</div>
<div class="form_group">
    <span class="iconfont icon-password  icon"></span>
    <input type="password" id="password" name="password" class="form-control" placeholder="请输入密码" required />
</div>
<div class="form_group captcha">
    <span class="iconfont icon-auth icon"></span>
    <input type="text" id="captcha" name="captcha" class="form-control" placeholder="请输入密码" required />
    <img src="captcha.php?mo=<?php echo time();?>" title="点击更新验证码" onClick="changeCaptcha();" />
</div>
<div class="button_box">
    <button class="sun_button sun_button_block">登录</button>
</div>
</form>
</div>

<div class="copyright">copyright © 2021 b5net.com all rights reserved.</div>

</body>
</html>