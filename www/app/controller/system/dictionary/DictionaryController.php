<?php
/**
 * 字典管理
 */
namespace app\controller\system\dictionary;

use \app\controller\BaseController;
use \app\model\system\DictionaryModel;
use \app\service\FrameMainService;
use \app\service\PaginationService;
use \app\service\SafeService;

class DictionaryController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = ''; // 框架菜单
        $dictionaryModel = new DictionaryModel(); // 模型
        $search = array(
            'type'=>''
        ); // 搜索
        $whereMarks = array();
        $whereValues = array();
        $where = array();
        $paginationService = null; // 分页
        $recordTotal = 0; // 总记录
        $paginationIntactNode = ''; // 节点
        $dictionarys = array();

        // 菜单
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_dictionary');

        // 搜索
        if(isset($_GET['type']) && $_GET['type'] !== ''){
            $whereMarks[] = 'type = :type';
            $whereValues[':type'] = $_GET['type'];
            $search['type'] = SafeService::entity($_GET['type']);
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        if(!empty($whereMarks)){
            $where['value'] = $whereValues;
        }
        
        $recordTotal = $dictionaryModel->getOne('count(1)', $where);
        
        $paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
        $paginationIntactNode = $paginationService->getNodeIntact();
        
        $dictionarys = $dictionaryModel->getAll('id, type, `key`, `value`, `sort`', $where, 'order by type asc, `sort` asc, id asc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);
        
        $dictionarys = SafeService::entity($dictionarys, array('id'));
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('dictionarys', $dictionarys);
        $this->assign('paginationIntactNode', $paginationIntactNode);
        $this->display('system/dictionary/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/dictionary/add.php');
    }
}