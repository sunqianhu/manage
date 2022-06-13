<?php

require_once 'library/app.php';

use think\facade\Db;
use library\model\BaseModel;
use library\service\ConfigService;

$baseModel = new BaseModel();


?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<script type="text/javascript" src="js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript">
$(function(){
    /*
    sun.dropDownHover({
        element: ".sun_dropdown"
    });
    */
    
    sun.fileUpload({
        element: ".sun_button",
        name: "file",
        url: "test_save.php",
        success: function(ret){
            alert(JSON.stringify(ret));
        }
    });
    
});
</script>
</head>

<body style="padding-left: 300px; padding-top: 100px">

<!--
<span class="sun_dropdown">
<div class="title">标题</div>
<div class="content">content</div>
</span>
-->

<span class="sun_button">上传</span>

<span class="sun_file_upload_progress" style="display: block">
<span class="chart"><span class="bg" style="width: 33px"></span></span>
<span class="text">22%</span>
</span>

<div class="sun_file_upload_result">
<div class="item">
<a href="">新建文本文档.txt</a>
<a href="" class="delete">删除</a>
</div>
<div class="item">
<a href="">新建文本文档.txt</a>
<a href="" class="delete">删除</a>
</div>
<div class="item">
<a href="">新建文本文档.txt</a>
<a href="" class="delete">删除</a>
</div>
</div>

</body>
</html>