<?php
/**
 * 角色
 */
namespace app\controller\system;

use app\controller\Base;
use app\service\FrameMain;

class Role extends Base{
    /**
     * 入口
     */
    function index(){
        $frameMainMenu = FrameMain::getMenu('system_role');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/role/index.php');
    }
}