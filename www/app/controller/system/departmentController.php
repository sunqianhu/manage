<?php
/**
 * 部门管理
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\service\MenuService;

class departmentController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = MenuService::getFrameMainHtml('system_department');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/department/index.php');
    }
    
    /**
     * 添加部门
     */
    function add(){
        $this->display('system/department/add.php');
    }
    
    /**
     * 添加选择部门
     */
    function addSelectDepartment(){
        $this->display('system/department/add_select_department.php');
    }
}