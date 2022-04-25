<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/index.css" rel="stylesheet" type="text/css" />
</head>

<body class="frame_main page">
<?php require_once __DIR__.'/inc/frame_main_header.php';?>
<div class="container">
<?php require_once __DIR__.'/inc/frame_main_left.php';?>
<div class="right">
首页
</div>
</div>
</body>
</html>