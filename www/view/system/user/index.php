<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link rel="stylesheet" href="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/css/bootstrap.min.css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/layui-2.6.8/css/layui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/layui-2.6.8/layui.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/user/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/user/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<a href="../../index/index">首页</a>
<span class="split">&gt;</span>
用户管理
</div>

<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>所属部门：<input type="text" name="xxx" /></li>
<li>用户名：<input type="text" name="xxx" /></li>
<li>手机号码：<input type="text" name="xxx" /></li>
<li>用户状态：<input type="text" name="xxx" /></li>
<li>登录时间：<span id="time_range"><input type="text" name="time_start" id="time_start" autocomplete="off" /> 到
<input type="text" name="time_end" id="time_end" autocomplete="off" />
</span></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
<input type="reset" class="sun_button sun_button_secondary sun_ml5" value="重置" />
</li>
</ul>
</form>
</div>

<div class="data sun_mt10">
<div class="toolbar">
<a href="javascript:;" class="sun_button" data-toggle="tooltip" title="添加用户" onClick="index.add();">添加</a>
</div>
<table class="sun_table sun_table_hover sun_mt10" width="100%">
    <thead>
      <tr>
        <th>用户id</th>
        <th>用户名</th>
        <th>姓名</th>
        <th>所属部门</th>
        <th>状态</th>
        <th>添加时间</th>
        <th width="160">操作</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>admin</td>
        <td>管理员</td>
        <td>部门1</td>
        <td>启用</td>
        <td>2022-05-06 13:34:25</td>
        <td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
<div class="sun_dropdown">
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_dropdown_button">更多<span class="iconfont icon-arrow_down icon"></span></a>
<ul class="sun_dropdown_menu">
<li><a href="javascript:;">启用</a></li>
</ul>
</div>

</td>
      </tr>
      <tr>
        <td>1</td>
        <td>admin</td>
        <td>管理员</td>
        <td>部门1</td>
        <td>启用</td>
        <td>2022-05-06 13:34:25</td>
        <td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
<div class="sun_dropdown">
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_dropdown_button">更多<span class="iconfont icon-arrow_down icon"></span></a>
<ul class="sun_dropdown_menu">
<li><a href="javascript:;">启用</a></li>
</ul>
</div>

</td>
      </tr>
    </tbody>
</table>

<div class="sun_pagination_intact sun_mt10">
<div class="count">共<span>100</span>条</div>
<div class="seat"></div>
<div class="limit">
每页显示<select>
<option value="10">10</option>
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
</body>
</html>