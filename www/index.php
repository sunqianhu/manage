<?php
/**
 * 首页
 */
require_once 'vendor/autoload.php';

use service\Auth;
use service\View;

if(!Auth::isLogin()){
    header('location:login/index.php');
    exit;
}

View::display('index.php');
?>