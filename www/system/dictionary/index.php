<?php
/**
 * 字典管理
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\DictionaryModel;
use library\service\ConfigService;
use library\service\FrameMainService;
use library\service\PaginationService;
use library\service\SafeService;
use library\service\AuthService;

$config = ConfigService::getAll();
$frameMainMenu = ''; // 框架菜单
$dictionaryModel = new DictionaryModel(); // 模型
$search = array(
    'type'=>''
); // 搜索
$whereMarks = array();
$whereValues = array();
$where = array();
$paginationService = null; // 分页
$recordTotal = 0; // 总记录
$paginationNodeIntact = ''; // 节点
$dictionarys = array();

if(!AuthService::isLogin()){
    header('location:../../login/index.php');
    exit;
}
if(!AuthService::isPermission('system_dictionary')){
    header('location:../../error.php?message='.urlencode('无权限'));
    exit;
}

// 菜单
$frameMainMenu = FrameMainService::getPageLeftMenu('system_dictionary');

// 搜索
if(isset($_GET['type']) && $_GET['type'] !== ''){
    $whereMarks[] = 'type = :type';
    $whereValues[':type'] = $_GET['type'];
    $search['type'] = SafeService::frontDisplay($_GET['type']);
}
if(!empty($whereMarks)){
    $where['mark'] = implode(' and ', $whereMarks);
}
if(!empty($whereMarks)){
    $where['value'] = $whereValues;
}

$recordTotal = $dictionaryModel->selectOne('count(1)', $where);

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

$dictionarys = $dictionaryModel->select('id, type, `key`, `value`, `sort`', $where, 'order by type asc, `sort` asc, id asc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);

$dictionarys = SafeService::frontDisplay($dictionarys, array('id'));

?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>字典管理_<?php echo $config['app_name'];?></title>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/jquery-1.12.4/jquery.min.js"></script>
<link href="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/plug/sun-1.0.0/sun.js"></script>
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/inc/frame_main.js"></script>
<link href="<?php echo $config['app_domain'];?>css/system/dictionary/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config['app_domain'];?>js/system/dictionary/index.js"></script>
</head>

<body class="page">
<?php require_once __DIR__.'/../../inc/frame_main_header.php';?>
<div class="page_body">
<?php require_once __DIR__.'/../../inc/frame_main_left.php';?>
<div class="page_right">
<div class="header">
<div class="location">
<span class="iconfont icon-home icon"></span>
<a href="../../index.html">系统首页</a> <span class="split">&gt;</span> 字典管理
</div>
<a href="javascript:;" onClick="location.reload();" class="refresh"><span class="iconfont icon-refresh icon"></span>刷新</a>
</div>
<div class="body">

<div class="search">
<form method="get" action="" class="form">
<ul>
<li>字典类型：<input type="text" name="type" value="<?php echo $search['type'];?>" /></li>
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
    <th>字典类型</th>
    <th>字典键</th>
    <th>字典值</th>
    <th>排序</th>
    <th width="100">操作</th>
  </tr>
<?php
if(!empty($dictionarys)){
foreach($dictionarys as $dictionary){
?>
  <tr>
    <td><?php echo $dictionary['id'];?></td>
    <td><?php echo $dictionary['type'];?></td>
    <td><?php echo $dictionary['key'];?></td>
    <td><?php echo $dictionary['value'];?></td>
    <td><?php echo $dictionary['sort'];?></td>
    <td>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small sun_mr5" onClick="index.edit(<?php echo $dictionary['id'];?>)">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small" onClick="index.delete(<?php echo $dictionary['id'];?>)">删除</a>
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