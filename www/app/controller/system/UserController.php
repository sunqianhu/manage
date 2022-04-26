<?php
/**
 * 用户管理
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\service\MenuService;

class UserController extends BaseController{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = MenuService::getFrameMainHtml('system_user');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/user/index.php');
    }
}