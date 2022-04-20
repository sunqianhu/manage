<?php
/**
 * 用户管理
 */
namespace app\controller\system;

use app\controller\Base;
use app\service\FrameMain;

class User extends Base{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = FrameMain::getMenu('system_user');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/user/index.php');
    }
}