<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $config['site_name'];?></title>
<link href="<?php echo $config['site_domain'];?>css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/index.js"></script>
</head>

<body class="body">
<div class="header">
<div class="sitename"><?php echo $config['site_name'];?></div>
<div class="link">
<ul>
<li><a href="">系统首页</a></li>
<li class="admin">
<a href="">孙乾户</a>
<ul class="dropdown_menu">
<li><a href="javascript:rePass();">修改密码</a></li>
<li><a href="<?php echo $config['site_domain'];?>index.php?c=login&a=exit">退出登录</a></li>
</ul>
</li>
</ul>
</div>
</div>
<div class="container">
<div class="left">
left
</div>
<div class="right">
right
</div>
</div>
</body>
</html>