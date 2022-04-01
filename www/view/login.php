<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>登录</title>
<link href="<?php echo $config['site_domain'];?>css/login.css" rel="stylesheet" type="text/css" />
</head>

<body class="page_login">
<div class="wrap">
<div class="title"><h1><?php echo $config['site_name'];?></h1></div>
<form class="form">
<div class="form_group">
    <span class="iconfont icon-user icon"></span>
    <input type="text" id="username" name="username" value="" placeholder="请输入用户名">
</div>
<div class="form_group">
    <span class="iconfont icon-password icon"></span>
    <input type="password" id="password" name="password" value="" placeholder="请输入密码">
</div>
<div class="button_box">
    <button class="button button_block">登录</button>
</div>
</form>
</div>

<div class="copyright">copyright © 2021 b5net.com all rights reserved.</div>

</body>
</html>