<?php
/**
 * 部门管理
 */
namespace app\controller\system\department;

use \app\controller\BaseController;
use \app\model\system\DepartmentModel;
use \app\service\ValidateService;
use \app\service\ZtreeService;
use \app\service\TreeService;
use \app\service\SafeService;
use \app\service\FrameMainService;
use \app\service\system\MenuService;
use \app\service\system\DepartmentService;

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
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_department');
        
        // 搜索
        if(!empty($_GET['id'])){
            $whereMarks[] = 'id = :id';
            $whereValues[':id'] = $_GET['id'];
            $search['id'] = SafeService::frontDisplay($_GET['id']);
        }
        if(isset($_GET['name']) && $_GET['name'] !== ''){
            $whereMarks[] = 'name like :name';
            $whereValues[':name'] = '%'.$_GET['name'].'%';
            $search['name'] = SafeService::frontDisplay($_GET['name']);
        }
        if(isset($_GET['remark']) && $_GET['remark'] !== ''){
            $whereMarks[] = 'remark like :remark';
            $whereValues[':remark'] = '%'.$_GET['remark'].'%';
            $search['remark'] = SafeService::frontDisplay($_GET['remark']);
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        $where['value'] = $whereValues;
        
        // 数据
        $departments = $departmentModel->getAll('id, parent_id, name, `sort`, remark', $where, 'order by `sort` asc, id asc');
        $departments = TreeService::getDataTree($departments, 'child', 'id', 'parent_id');
        $departments = TreeService::addLevel($departments, 1);
        $departments = SafeService::frontDisplay($departments, array('id', 'parent_id'));
        $departmentNode = DepartmentService::getIndexTreeNode($departments);
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('departmentNode', $departmentNode);
        $this->display('system/department/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/department/add.php');
    }
    
    /**
     * 添加选择部门
     */
    function addSelectDepartment(){
        $departmentModel = new DepartmentModel();
        $departments = array();
        $department = ''; // 部门json数据
        
        $departments = $departmentModel->getAll('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $departments = ZtreeService::setOpenByFirst($departments);
        $department = json_encode($departments);
        
        $this->assign('department', $department);
        $this->display('system/department/add_select_department.php');
    }
    
    /**
     * 添加保存
     */
    function addSave(){
        $return = array(
            'status'=>'error',
            'msg'=>'',
            'data'=>array(
                'dom'=>''
            )
        ); // 返回数据
        $validateService = new ValidateService();
        $departmentModel = new DepartmentModel();
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
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
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
            $return['message'] = $e->getMessage();
            echo json_encode($return);
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
        
        $return['status'] = 'success';
        $return['message'] = '添加成功';
        echo json_encode($return);
    }
    
    /**
     * 修改
     */
    function edit(){
        $validateService = new ValidateService();
        $departmentModel = new DepartmentModel();
        $department = array();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require|number'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字'
        );
        if(!$validateService->check($_GET)){
            header('location:../../error.html?message='.urlencode($validateService->getErrorMessage()));
            exit;
        }
        if($_GET['id'] == '1'){
            header('location:../../error.html?message='.urlencode('根部门不能修改'));
            exit;
        }
        
        $department = $departmentModel->getRow('id, parent_id, name, `sort`, remark', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        $department['parent_name'] = $departmentModel->getOne('name', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=> $department['parent_id']
            )
        ));
        $department = SafeService::frontDisplay($department, array('id', 'parent_id'));
        
        $this->assign('department', $department);
        $this->display('system/department/edit.php');
    }
    
    /**
     * 修改选择部门
     */
    function editSelectDepartment(){
        $departmentModel = new DepartmentModel();
        $departments = array();
        $department = ''; // 部门json数据
        
        $departments = $departmentModel->getAll('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $departments = ZtreeService::setOpenByFirst($departments);
        $department = json_encode($departments);
        
        $this->assign('department', $department);
        $this->display('system/department/edit_select_department.php');
    }
    
    /**
     * 修改保存
     */
    function editSave(){
        $return = array(
            'status'=>'error',
            'msg'=>'',
            'data'=>array(
                'dom'=>''
            )
        ); // 返回数据
        $validateService = new ValidateService();
        $departmentModel = new DepartmentModel();
        $departmentCurrent = array(); // 本部门
        $departmentParent = array(); // 上级部门
        $data = array();
        

        // 验证
        $validateService->rule = array(
            'id' => 'require|number',
            'parent_id' => 'number',
            'name' => 'require|max_length:25',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字',
            'parent_id.number' => '请选择上级部门',
            'name.require' => '请输入部门名称',
            'name.max_length' => '部门名称不能大于32个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        if($_POST['id'] == '1'){
            $return['message'] = '不能修改根部门';
            echo json_encode($return);
            exit;
        }
        
        // 本部门
        $departmentCurrent = $departmentModel->getRow(
            'id, parent_id',
            array(
                'mark'=> 'id = :id',
                'value'=> array(
                    ':id'=>$_POST['id']
                )
            )
        );
        if(empty($departmentCurrent)){
            $return['message'] = '此部门没有找到';
            echo json_encode($return);
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
        
        // 更新
        $data = array(
            'parent_id'=>$_POST['parent_id'],
            'parent_ids'=>$departmentParent['parent_ids'].','.$departmentCurrent['id'],
            'name'=>$_POST['name'],
            'sort'=>$_POST['sort'],
            'remark'=>$_POST['remark']
        );
        try{
            $id = $departmentModel->update($data, array(
                'mark'=>'id = :id',
                'value'=> array(
                    ':id'=>$departmentCurrent['id']
                )
            ));
        }catch(Exception $e){
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        $return['status'] = 'success';
        $return['message'] = '修改成功';
        echo json_encode($return);
    }
    
    /**
     * 删除
     */
    function delete(){
        $return = array(
            'status'=>'error',
            'message'=>''
        );
        $departmentChild = array();
        $departmentModel = new DepartmentModel();
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
            $return['message'] = $validateService->getErrorMessage();
            echo json_encode($return);
            exit;
        }
        if($_GET['id'] == '1'){
            $return['message'] = '不能删除根部门';
            echo json_encode($return);
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
            $return['message'] = '该部门存在下级部门';
            echo json_encode($return);
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
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        $return['status'] = 'success';
        $return['message'] = '删除成功';
        echo json_encode($return);
    }
}