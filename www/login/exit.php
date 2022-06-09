<?php
/**
 * 退出
 */
require_once '../library/session.php';
require_once '../library/autoload.php';

use library\service\AuthService;

unset($_SESSION['user']);
unset($_SESSION['department']);
unset($_SESSION['menu']);

header('location:index.php');
?>