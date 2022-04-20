<?php
/**
 * 首页
 */
namespace app\controller;

use app\service\FrameMain;

class Index extends Base{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = FrameMain::getMenu('');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('index.php');
    }
}