<?php
/**
 * 部门管理
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\service\MenuService;
use app\model\system\DepartmentModel;
use app\service\ValidateService;
use app\service\ZtreeService;
use app\service\TreeService;
use app\service\SafeService;
use app\service\system\DepartmentService;
use app\service\ResponseService;

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
            'name'=>''
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
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        $where['value'] = $whereValues;
        
        // 数据
        $departments = $departmentModel->getAll('id, parent_id, name, `sort`', $where, 'order by `sort` asc, id asc');
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
        $departmentModel = new departmentModel();
        $departments = array();
        $department = ''; // 部门json数据
        
        $departments = $departmentModel->getAll('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $departments = ZtreeService::setOpenByFirst($departments);
        $department = json_encode($departments);
        $this->assign('department', $department);
        
        $this->display('system/department/add_select_department.php');
    }
    
    /**
     * 添加部门保存
     */
    function addSave(){
        $validateService = new ValidateService();
        $departmentModel = new departmentModel();
        $departmentParent = array(); // 上级部门
        $id = 0; // 添加部门id
        $parentIds = ''; // 所有上级部门id
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'parent_id' => 'number',
            'name' => 'require|max_length:25',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'parent_id.number' => '请选择上级部门',
            'name.require' => '请输入部门名称',
            'name.max_length' => '部门名称不能大于32个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            echo ResponseService::json('error', $validateService->getErrorMessage(), array('dom'=>$validateService->getErrorField()));
            exit;
        }
        
        // 上级部门
        $departmentParent = $departmentModel->getRow(
            'parent_ids',
            array(
                'mark'=> 'id = :id',
                'value'=> array(
                    ':id'=>$_POST['parent_id']
                )
            )
        );
        
        // 入库
        $data = array(
            'parent_id'=>$_POST['parent_id'],
            'name'=>$_POST['name'],
            'sort'=>$_POST['sort'],
            'remark'=>$_POST['remark']
        );
        try{
            $id = $departmentModel->insert($data);
        }catch(Exception $e){
            echo ResponseService::json('error', $e->getMessage());
            exit;
        }
        
        $parentIds = $departmentParent['parent_ids'].','.$id;
        $departmentModel->update(
            array('parent_ids'=>$parentIds),
            array(
                'mark'=>'id = :id',
                'value'=> array(
                    ':id'=>$id
                )
            )
        );
        
        echo ResponseService::json('success', '添加成功');
    }
    
    /**
     * 删除
     */
    function delete(){
        $departmentChild = array();
        $departmentModel = new departmentModel();
        $validateService = new ValidateService();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require:number'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字'
        );
        if(!$validateService->check($_GET)){
            echo ResponseService::json('error', $validateService->getErrorMessage());
            exit;
        }
        
        $departmentChild = $departmentModel->getRow(
            'id',
            array(
                'mark'=>'parent_id = :id',
                'value'=> array(
                    ':id'=>$_GET['id']
                )
            )
        );
        if(!empty($departmentChild)){
            echo ResponseService::json('error', '该部门存在下级部门');
            exit;
        }
        
        try{
            $departmentModel->delete(
                array(
                    'mark'=>'id = :id',
                    'value'=> array(
                        ':id'=>$_GET['id']
                    )
                )
            );
        }catch(Exception $e){
            echo ResponseService::json('error', $e->getMessage());
            exit;
        }
        
        echo ResponseService::json('success', '删除成功');
    }
}