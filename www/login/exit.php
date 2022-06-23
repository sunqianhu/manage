<?php
/**
 * 退出
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\service\AuthService;

unset($_SESSION['user']);
unset($_SESSION['department']);
unset($_SESSION['permission']);

header('location:index.php');
?>