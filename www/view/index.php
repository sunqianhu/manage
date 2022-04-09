<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $config['site_name'];?></title>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['site_domain'];?>css/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config['site_domain'];?>css/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config['site_domain'];?>css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/index.js"></script>
</head>

<body class="body">
<div class="header">
<div class="sitename"><?php echo $config['site_name'];?></div>
<div class="link">
<ul>
<li><a href="">系统首页</a></li>
<li class="user">
<a href="">孙乾户</a>
<ul class="dropdown_menu">
<li><a href="javascript:rePass();">修改密码</a></li>
<li><a href="<?php echo $config['site_domain'];?>login/exit">退出登录</a></li>
</ul>
</li>
</ul>
</div>
</div>
<div class="container">
<div class="left">
<div class="menu">
<ul class="nav nav-list">
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">系统管理</span>
<span class="icon icon-xxx arrow"></span>
</a>
<ul>
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">用户管理</span>
</a>
</li>
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">角色管理</span>
</a>
</li>
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">权限管理</span>
</a>
</li>
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">字典管理</span>
</a>
</li>
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">登录日志</span>
</a>
</li>
<li>
<a href="#" class="dropdown-toggle">
<span class="icon icon-xxx icon"></span>
<span class="text">访问日志</span>
</a>
</li>
</ul>
</li>
</ul>
</div>
</div>
<div class="right">
right
</div>
</div>
</body>
</html>