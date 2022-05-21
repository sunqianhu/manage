<?php
/**
 * 部门管理
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\model\system\DepartmentModel;

class DepartmentController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $departmentModel = new DepartmentModel();
        $departments = array(); // 部门数据
        $departmentNode = ''; // 部门表格节点
        $frameMainMenu = '';
        $search = array(
            'id'=>'',
            'name'=>'',
            'remark'=>''
        );
        $whereMarks = array();
        $whereValues = array();
        $where = array();
        
        // 菜单
        $frameMainMenu = MenuService::getFrameMainHtml('system_department');
        
        // 搜索
        if(!empty($_GET['id'])){
            $whereMarks[] = 'id = :id';
            $whereValues[':id'] = $_GET['id'];
            $search['id'] = SafeService::entity($_GET['id']);
        }
        if(isset($_GET['name']) && $_GET['name'] !== ''){
            $whereMarks[] = 'name like :name';
            $whereValues[':name'] = '%'.$_GET['name'].'%';
            $search['name'] = SafeService::entity($_GET['name']);
        }
        if(isset($_GET['remark']) && $_GET['remark'] !== ''){
            $whereMarks[] = 'remark like :remark';
            $whereValues[':remark'] = '%'.$_GET['remark'].'%';
            $search['remark'] = SafeService::entity($_GET['remark']);
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        $where['value'] = $whereValues;
        
        // 数据
        $departments = $departmentModel->getAll('id, parent_id, name, `sort`, remark', $where, 'order by `sort` asc, id asc');
        $departments = TreeService::getDataTree($departments, 'child', 'id', 'parent_id');
        $departments = TreeService::addLevel($departments, 1);
        $departments = SafeService::entity($departments, array('id', 'parent_id'));
        $departmentNode = DepartmentService::getIndexTreeNode($departments);
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('departmentNode', $departmentNode);
        $this->display('system/department/index.php');
    }
    
    
}