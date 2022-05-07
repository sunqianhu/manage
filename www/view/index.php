<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/index.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<?php require_once __DIR__.'/inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<span class="page_name">系统首页</span>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="welcome">
欢迎<span>运营单位孙乾户</span>登录系统，上次登录时间：2022-05-06 10:57:12，上次登录IP：218.88.23.158<div>
</div>
</div>


</div>
</div>
</div>
</body>
</html>