<?php
/**
 * 验证码
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\Captcha;

Captcha::create('login_captcha');
?>