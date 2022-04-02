<?php
/**
 * 首页
 */
namespace app\controller;

use \app\View;

class Index extends Base{
    /**
     * 入口
     */
    function main(){
        $this->display("index.php");
    }
}