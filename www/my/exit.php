<?php
/**
 * 退出
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\Auth;

unset($_SESSION['user']);
unset($_SESSION['department']);
unset($_SESSION['permission']);

header('location:login.php');
?>