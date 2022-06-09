<?php
/*
 * 自动加载
 */

ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');
header('Content-type: text/html; charset=utf-8');

require_once dirname(__DIR__).'/vendor/autoload.php';
?>