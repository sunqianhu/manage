<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>系统提示</title>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/error.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">
<div class="container">
<div class="title"><h1>系统提示</h1></div>
<div class="message"><?php echo $message;?></div>
</div>
</body>
</html>