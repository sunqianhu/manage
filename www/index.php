<?php
require_once 'vendor/autoload.php';
require_once 'error.php';

use app\Route;

$controller = null; // 控制器
$action = ''; // 方法

$controller = Route::getController();
$action = Route::getAction();
$controller->$action();
?>