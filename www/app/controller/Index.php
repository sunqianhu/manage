<?php
/**
 * 首页
 */
namespace app\controller;

class Index extends Base{
    /**
     * 入口
     */
    function index(){
        $this->display('index.php');
    }
}