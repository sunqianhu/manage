<?php
/**
 * 首页
 */
namespace app\controller;

use \app\controller\Base;
use \app\View;
use \app\model\User;

class Index extends Base{
    /**
     * 入口
     */
    function main(){
        header('location:index.php?c=Login&a=main');
        exit;
        $this->display("index.php");
    }
}