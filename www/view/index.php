<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $config['site_name'];?></title>
<link href="<?php echo $config['site_domain'];?>css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $config['site_domain'];?>js/index.js"></script>
</head>

<body class="body">
<div class="header">
<div class="sitename"><?php echo $config['site_name'];?></div>
<div class="link">
<ul>
<li><a href="">系统首页</a></li>
<li class="user">
<a href="">孙乾户</a>
<ul class="dropdown_menu">
<li><a href="javascript:rePass();">修改密码</a></li>
<li><a href="<?php echo $config['site_domain'];?>index.php?c=login&a=exit">退出登录</a></li>
</ul>
</li>
</ul>
</div>
</div>
<div class="container">
<div class="left">
<div class="menu">
<ul class="nav nav-list">
    <li class="">
        <a href="#" class="dropdown-toggle">
<i class="fa fa-cogs normal"></i>
<span class="menu-text normal"> 设置 </span>
<b class="arrow fa fa-angle-right normal"></b>
<i class="fa fa-reply back"></i>
<span class="menu-text back">返回</span>

</a>
    
        <ul class="submenu" style="display: none;">
            <li>
                <a href="javascript:openapp('/user/setting/site.html','71user','网站信息',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
网站信息								</span>
</a>
            
            </li>
            <li>
                <a href="javascript:openapp('/user/mailer/index.html','15user','邮箱配置',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
邮箱配置								</span>
</a>
            
            </li>
            <li>
                <a href="javascript:openapp('/user/theme/index.html','95user','模板管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
模板管理								</span>
</a>
            
            </li>

            <li>
                <a href="javascript:openapp('/user/nav/index.html','29user','导航管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
导航管理								</span>
</a>
            

            </li>
            <li>
                <a href="javascript:openapp('/user/slide/index.html','78user','幻灯片管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
幻灯片管理								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/link/index.html','7user','友情链接',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
友情链接								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/route/index.html','61user','URL美化',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
URL美化								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/setting/upload.html','75user','上传设置',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
上传设置								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/storage/index.html','93user','文件存储',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
文件存储								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/user_user_action/index.html','129user','用户操作管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
用户操作管理								</span>
</a>
            





            </li>

        </ul>

    </li>

    <li class="open">
        <a href="#" class="dropdown-toggle">
<i class="fa fa-group normal"></i>
<span class="menu-text normal"> 用户管理 </span>
<b class="arrow fa fa-angle-right normal"></b>
<i class="fa fa-reply back"></i>
<span class="menu-text back">返回</span>

</a>
    





        <ul class="submenu" style="display: block;">
            <li class="open">
                <a href="#" class="dropdown-toggle">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
管理组								</span>
<b class="arrow fa fa-angle-right"></b>
</a>
            




                <ul class="submenu" style="display: block;">
                    <li>

                        <a href="javascript:openapp('/user/rbac/index.html','50user','角色管理',true);">
&nbsp;<i class="fa fa-angle-double-right"></i>
<span class="menu-text">
角色管理							</span>
</a>
                    




                    </li>

                    <li>

                        <a href="javascript:openapp('/user/user/index.html','111user','管理员',true);">
&nbsp;<i class="fa fa-angle-double-right"></i>
<span class="menu-text">
管理员							</span>
</a>
                    




                    </li>

                </ul>

            </li>

            <li>
                <a href="#" class="dropdown-toggle">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
用户组								</span>
<b class="arrow fa fa-angle-right"></b>
</a>
            




                <ul class="submenu">
                    <li>

                        <a href="javascript:openapp('/user/user_index/index.html','124user','本站用户',true);">
&nbsp;<i class="fa fa-angle-double-right"></i>
<span class="menu-text">
本站用户							</span>
</a>
                    




                    </li>

                    <li>

                        <a href="javascript:openapp('/user/user_oauth/index.html','127user','第三方用户',true);">
&nbsp;<i class="fa fa-angle-double-right"></i>
<span class="menu-text">
第三方用户							</span>
</a>
                    




                    </li>

                </ul>

            </li>

        </ul>

    </li>

    <li>
        <a href="#" class="dropdown-toggle">
<i class="fa fa-cloud normal"></i>
<span class="menu-text normal"> 应用中心 </span>
<b class="arrow fa fa-angle-right normal"></b>
<i class="fa fa-reply back"></i>
<span class="menu-text back">返回</span>

</a>
    





        <ul class="submenu">
            <li>
                <a href="javascript:openapp('/user/hook/index.html','2user','钩子管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
钩子管理								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/plugin/index.html','42user','插件管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
插件管理								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/app/index.html','162user','应用管理',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
应用管理								</span>
</a>
            





            </li>

            <li>
                <a href="javascript:openapp('/user/app_store/apps.html','167user','应用市场',true);">
<i class="fa fa-caret-right"></i>
<span class="menu-text">
应用市场								</span>
</a>
            





            </li>

        </ul>

    </li>

</ul>
</div>
</div>
<div class="right">
right
</div>
</div>
</body>
</html>