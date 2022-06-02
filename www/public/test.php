<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<script type="text/javascript" src="js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/plug/sun-1.0.0/sun.js"></script>
<link href="js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
    sun.dropdownMenu({
        selector: ".sun_dropdown_menu"
    });
});
</script>
</head>

<body style="padding: 100px">

<span class="sun_dot sun_dot_empty"></span>

<div class="sun_button_group">
<a class="sun_button">按钮1</a>
<a class="sun_button sun_button_warning">按钮2</a>
<a class="sun_button">按钮3</a>
</div>

<div class="sun_tab_brief">
<div class="title">
<a href="" class="active">选卡1</a>
<a href="">选卡2</a>
<a href="">选卡3</a>
</div>
<div class="content">内容1</div>
<div class="content">内容2</div>
<div class="content">内容3</div>
</div>
<br>
<br>
<span class="green">启用</span>
&nbsp; &nbsp; &nbsp; &nbsp; <span class="sun_badge sun_badge_gray">启用</span>
  
  <span class="sun_badge">停用</span>

 &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
<span class="sun_dropdown_menu">
<div class="title">title</div>
<div class="content">
<ul>
<li><a href="#">菜单1</a></li>
<li><a href="#">菜单2</a></li>
<li><a href="#">菜单3</a></li>
</ul>
</div>
</span>

</body>
</html>