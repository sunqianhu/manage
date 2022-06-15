<div class="page_header">
<div class="name"><?php echo $config['app_name'];?></div>
<div class="switch"><span class="iconfont icon-switch_left icon" onclick="frameMain.pageLeftToggle();" title="打开或关闭左边菜单"></span></div>
<div class="link">
<ul>
<li class="user">
<a href="javascript:;" class="title">
<img src="<?php echo $_SESSION['user']['head_url'];?>" />
<?php echo $_SESSION['user']['name'];?>
</a>
<div class="content">
<ul>
<li><a href="javascript:;" onClick="frameMain.editUserHead();">修改头像</a></li>
<li><a href="javascript:;" onClick="frameMain.editUserPassword();">修改密码</a></li>
<li><a href="<?php echo $config['app_domain'];?>login/exit.php">退出登录</a></li>
</ul>
</div>
</li>
</ul>
</div>
</div>