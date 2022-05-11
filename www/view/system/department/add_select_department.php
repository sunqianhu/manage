<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选择部门</title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery/jquery-1.12.4.min.js"></script>

<link href="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/ztree-3.5.48/js/jquery.ztree.core.min.js"></script>

<link href="<?php echo $config['app_domain'];?>css/system/department/add_select_department.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/department/add_select_department.js"></script>
</head>

<body class="page">
<form method="post" action="addSave" class="sun_form">
<ul id="treeDemo" class="ztree"></ul>
</form>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">
   var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {};
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = [
   {name:"test1", open:true, children:[
      {name:"test1_1"}, {name:"test1_2"}]},
   {name:"test2", open:true, children:[
      {name:"test2_1"}, {name:"test2_2"}]}
   ];
   $(document).ready(function(){
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   });
  </SCRIPT>