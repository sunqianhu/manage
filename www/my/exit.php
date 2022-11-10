<?php
/**
 * 退出
 */
require_once '../main.php';

unset($_SESSION['user']);
unset($_SESSION['department']);
unset($_SESSION['permission']);

header('location:login.php');
?>