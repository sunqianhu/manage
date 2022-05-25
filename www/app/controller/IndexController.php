<?php
/**
 * 首页
 */
namespace app\controller;

use \app\service\system\MenuService;

class IndexController extends BaseController{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = FrameMainService::getPageLeftMenu('home');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('index.php');
    }
}