<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'error.php';

use app\Route;

Route::run();
?>