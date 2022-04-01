<?php
/**
 * 登录
 */
namespace app\controller;

use app\controller\Base;
use app\View;

class Login extends Base{
    /**
     * 入口
     */
    function main(){
        $this->display("login.php");
    }
}