<?php
/**
 * 退出
 */
require_once '../library/app.php';

use \library\Session;
use \library\Auth;

Session::start();

unset($_SESSION['user']);
unset($_SESSION['department']);
unset($_SESSION['permission']);

header('location:login.php');
?>