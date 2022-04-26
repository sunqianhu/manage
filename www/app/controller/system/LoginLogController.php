<?php
/**
 * 登录日志
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\service\MenuService;

class LoginLogController extends BaseController{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = MenuService::getFrameMainHtml('system_login_log');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/login_log/index.php');
    }
}