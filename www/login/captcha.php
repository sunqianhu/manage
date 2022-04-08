<?php
/**
 * 验证码
 */
require_once '../vendor/autoload.php';

use service\Captcha;

Captcha::createImage('captcha_login');
?>