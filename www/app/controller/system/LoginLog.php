<?php
/**
 * 登录日志
 */
namespace app\controller\system;

use app\controller\Base;
use app\service\FrameMain;

class LoginLog extends Base{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = FrameMain::getMenu('system_login_log');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/login_log/index.php');
    }
}