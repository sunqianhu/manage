<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>部门管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link rel="stylesheet" href="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/css/bootstrap.min.css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/layui-2.6.8/css/layui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/layui-2.6.8/layui.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/department/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<a href="../../index/index">首页</a> <span class="split">&gt;</span> 部门管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>部门id：<input type="text" name="xxx" /></li>
<li>部门名称：<input type="text" name="xxx" /></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
<input type="reset" class="sun_button sun_button_secondary sun_ml5" value="重置" />
</li>
</ul>
</form>
</div>

<div class="data sun_mt10">
<div class="toolbar">
<a href="javascript:;" class="sun_button" data-toggle="tooltip" title="添加部门" onClick="index.add();">添加</a>
</div>
<table class="sun_table sun_table_hover sun_mt10" width="100%">
    <thead>
      <tr>
        <th>部门id</th>
        <th>部门名称</th>
        <th>部门编号</th>
        <th>排序</th>
        <th>添加时间</th>
        <th width="160">操作</th>
      </tr>
    </thead>
    <tbody>
      <tr id="tr_1" parent_id="0">
        <td>1</td>
        <td class="name"><span class="iconfont icon-sjx_right"></span>部门1</td>
        <td>100</td>
        <td>1</td>
        <td>2022-05-06 13:34:25</td>
        <td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5" data-toggle="tooltip" title="添加子部门">添加</a>
<a href="javascript:;" class="sun_button sun_button_sm sun_button_secondary sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
</td>
      </tr>
      <tr id="tr_2" parent_id="tr_1">
        <td>2</td>
        <td><span class="indent"></span>部门1_1</td>
        <td>100100</td>
        <td>1</td>
        <td>2022-05-06 13:34:25</td>
        <td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5">添加</a>
<a href="javascript:;" class="sun_button sun_button_sm sun_button_secondary sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
</td>
      </tr>
      <tr id="tr_3" parent_id="tr_1">
        <td>3</td>
        <td><span class="indent"></span><span class="indent"></span>部门1_1_1</td>
        <td>100100100</td>
        <td>1</td>
        <td>2022-05-06 13:34:25</td>
        <td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5">添加</a>
<a href="javascript:;" class="sun_button sun_button_sm sun_button_secondary sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
</td>
      </tr>
      <tr id="tr_4" parent_id="0">
        <td>4</td>
        <td class="name"><span class="iconfont icon-sjx_right"></span>部门2</td>
        <td>101</td>
        <td>1</td>
        <td>2022-05-06 13:34:25</td>
        <td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5" data-toggle="tooltip" title="添加子部门">添加</a>
<a href="javascript:;" class="sun_button sun_button_sm sun_button_secondary sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
</td>
      </tr>
    </tbody>
</table>

<div class="sun_pagination sun_mt10">
<span class="count">共<span>100</span>条</span>

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