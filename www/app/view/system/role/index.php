<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>角色管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/role/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/role/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.html">系统首页</a> <span class="split">&gt;</span> 角色管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>角色名称：<input type="text" name="name" value="<?php echo $search['name'];?>" /></li>
<li>
<input type="submit" value="搜索" class="sun_button" />
</li>
</ul>
</form>
</div>

<div class="data sun_mt10">
<div class="toolbar">
<a href="javascript:;" class="sun_button" onClick="index.add();">添加</a>
</div>
<table class="sun_table sun_table_hover sun_mt10" width="100%">
  <tr>
    <th>id</th>
    <th>角色名称</th>
    <th>最后修改时间</th>
    <th width="100">操作</th>
  </tr>
<?php
if(!empty($roles)){
foreach($roles as $role){
?>
  <tr>
    <td><?php echo $role['id'];?></td>
    <td><?php echo $role['name'];?></td>
    <td><?php echo $role['time_edit_name'];?></td>
    <td>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small sun_mr5" onClick="index.edit(<?php echo $role['id'];?>)">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small" onClick="index.delete(<?php echo $role['id'];?>)">删除</a>
    </td>
  </tr>
<?php
}
}else{
?>
<tr>
<td colspan="5" align="center">无数据</td>
</tr>
<?php
}
?>
</table>
<?php echo $paginationNodeIntact;?>
</div>

</div>
</div>
</div>
</body>
</html>