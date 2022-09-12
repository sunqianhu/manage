<?php
/**
 * 验证码
 */
require_once '../library/app.php';

use \library\Session;
use \library\Captcha;

Session::start();

$code = '';

$code = Captcha::create();
$_SESSION['login_captcha'] = $code;
?>