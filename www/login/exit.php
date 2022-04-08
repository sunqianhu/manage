<?php
/**
 * 退出登录
 */
require_once '../vendor/autoload.php';

use service\Auth;

Auth::unsetSessionUser();

header('location:index.php');
?>