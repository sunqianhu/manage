<?php
/*
 * 应用入口
 */

// 设置
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');
header('Content-type: text/html; charset=utf-8');

// 自动加载
require_once __DIR__.'/vendor/autoload.php';

use library\core\Session;
use library\helper\OperationLog;

$session = new Session();
$operationLog = new OperationLog();

$session->start();
$operationLog->add();
?>