<?php
/**
 * 字典管理
 */
namespace app\controller\system\dictionary;

use \app\controller\BaseController;
use \app\model\system\DictionaryModel;
use \app\service\FrameMainService;
use \app\service\PaginationService;

class DictionaryController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = '';
        $dictionaryModel = new DictionaryModel(); // 字典模型
        $paginationIntact = '';
        
        $paginationService = new PaginationService(100, $_GET['page_size'], $_GET['page_current']);
        $paginationIntact = $paginationService->getIntact();
        
        // 菜单
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_dictionary');
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('paginationIntact', $paginationIntact);
        $this->display('system/dictionary/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/dictionary/add.php');
    }
}