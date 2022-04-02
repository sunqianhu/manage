<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'system_error_handler.php';

use app\Route;

Route::run();
?>