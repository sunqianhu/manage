<?php
/**
 * 验证码
 */
require_once '../library/app.php';

use library\Session;
use library\Captcha;

$code = '';
$captcha = new Captcha();

$code = $captcha->create();
$_SESSION['login_captcha'] = $code;
?>