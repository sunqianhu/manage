<?php
/**
 * 验证码
 */
require_once '../library/session.php';
require_once '../library/autoload.php';

use library\service\CaptchaService;

CaptchaService::create('login_captcha');
?>