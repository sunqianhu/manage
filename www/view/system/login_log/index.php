<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>登录日志_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/layui-2.6.8/css/layui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/layui-2.6.8/layui.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/login_log/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/login_log/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<span class="page_name">登录日志</span>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>登录时间：<span id="time_range"><input type="text" name="time_start" id="time_start" autocomplete="off" /> 到
<input type="text" name="time_end" id="time_end" autocomplete="off" />
</span></li>
<li>用户名：<input type="text" name="xxx" /></li>
<li>登录状态：<select name="status">
<option value="0">不限</option>
<option value="1">登录成功</option>
<option value="2">登录失败</option>
</select></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
</li>
</ul>
</form>
</div>

<div class="data sun_mt10">
<table class="sun_table sun_table_hover" width="100%">
    <thead>
      <tr>
        <th>日志id</th>
        <th>登录名称</th>
        <th>登录ip</th>
        <th>登录时间</th>
        <th>状态</th>
        <th>登录信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>admin</td>
        <td>127.0.0.1</td>
        <td>2022-05-06 14:16:56</td>
        <td>成功</td>
        <td>登录成功</td>
      </tr>
      <tr>
        <td>1</td>
        <td>admin</td>
        <td>127.0.0.1</td>
        <td>2022-05-06 14:16:56</td>
        <td>成功</td>
        <td>登录成功</td>
      </tr>
      <tr>
        <td>1</td>
        <td>admin</td>
        <td>127.0.0.1</td>
        <td>2022-05-06 14:16:56</td>
        <td>成功</td>
        <td>登录成功</td>
      </tr>
      <tr>
        <td>1</td>
        <td>admin</td>
        <td>127.0.0.1</td>
        <td>2022-05-06 14:16:56</td>
        <td>成功</td>
        <td>登录成功</td>
      </tr>
    </tbody>
</table>

<div class="sun_pagination_intact sun_mt10">
<div class="left">
<span class="count">共<span>100</span>条</span>
</div>
<div class="right">
<div class="limit">
每页显示<select>
<option value="10" selected="">10</option>
<option value="20">20</option>
<option value="30">30</option>
<option value="40">40</option>
<option value="50">50</option>
</select>条
</div>

<div class="skip">
到第<input type="text" min="1" value="1" class="number">页
<button type="button" class="sun_button sun_button_sm sun_button_secondary">确定</button>
</div>

<div class="link">
<a href="javascript:;" class="prev disabled" data-page="0">上一页</a>
<a href="javascript:;" class="active">1</a>
<a href="javascript:;">2</a>
<a href="javascript:;">3</a>
<a href="javascript:;">4</a>
<a href="javascript:;">5</a>
<a href="javascript:;" class="last" title="尾页">10</a>
<a href="javascript:;" class="next">下一页</a>
</div>

</div>
</div>

</div>

</div>
</div>
</div>
</body>
</html>