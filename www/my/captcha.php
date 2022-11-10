<?php
/**
 * 验证码
 */
require_once '../main.php';

use library\core\Captcha;

$code = '';
$captcha = new Captcha();

$code = $captcha->create();
$_SESSION['login_captcha'] = $code;
?>