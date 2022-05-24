<?php
/**
 * 字典管理
 */
namespace app\controller\system\dictionary;

use \app\controller\BaseController;
use \app\service\system\MenuService;

class DictionaryController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = '';
        
        // 菜单
        $frameMainMenu = MenuService::getFrameMainNode('system_menu');
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/dictionary/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/dictionary/add.php');
    }
}