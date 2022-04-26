<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/bootstrap-5.1.3/css/bootstrap.min.css" rel="stylesheet" />
<script src="<?php echo $config['app_domain'];?>js/plug/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="body_right">
<div class="main_header">
<span class="page_name">用户管理</span>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="main_body">

<div class="section_search">
<form method="get" action="" class="form">
<ul>
<li>所属部门：<input type="text" name="xxx" /></li>
<li>用户名：<input type="text" name="xxx" /></li>
<li>手机号码：<input type="text" name="xxx" /></li>
<li>用户状态：<input type="text" name="xxx" /></li>
<li>最后登录时间：<input type="text" name="xxx" /></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
<input type="reset" class="sun_button sun_button_secondary sun_ml5" value="重置" />
</li>
</ul>
</form>
</div>

<div class="section_data">
<div class="toolbar">
<a href="" class="sun_button" data-bs-toggle="tooltip" data-bs-placement="top" title="添加用户">添加</a>
</div>
<table class="table">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
      </tr>
    </tbody>
</table>
<ul class="pagination">
  <li class="page-item"><a class="page-link" href="#">Previous</a></li>
  <li class="page-item"><a class="page-link" href="#">1</a></li>
  <li class="page-item active"><a class="page-link" href="#">2</a></li>
  <li class="page-item"><a class="page-link" href="#">3</a></li>
  <li class="page-item"><a class="page-link" href="#">Next</a></li>
</ul>
</div>

</div>
</div>
</div>
</body>
</html>