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
require_once dirname(__DIR__).'/vendor/autoload.php';

// 日志
use library\service\system\OperationLogService;
OperationLogService::add();
?>