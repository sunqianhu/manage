<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/app/frame_main.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/index.js"></script>
</head>

<body class="app_frame_main body">
<?php require_once 'inc/frame_main_header.php';?>
<div class="container">
<?php require_once 'inc/frame_main_left.php';?>
<div class="right">
内容区域
</div>
</div>
</body>
</html>