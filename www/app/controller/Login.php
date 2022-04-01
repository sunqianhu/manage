<?php
/**
 * 登录
 */
namespace app\controller;

use app\controller\Base;
use app\View;
use app\helper\Captcha;

class Login extends Base{
    /**
     * 入口
     */
    function main(){
        $this->display("login.php");
    }
    
    /**
     * 验证码
     */
    function captcha(){
        Captcha::createImage();
    }
    
}