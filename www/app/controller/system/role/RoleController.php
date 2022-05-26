<?php
/**
 * 角色管理
 */
namespace app\controller\system\role;

use \app\controller\BaseController;
use \app\model\system\RoleModel;
use \app\service\FrameMainService;
use \app\service\PaginationService;
use \app\service\SafeService;
use \app\service\ValidateService;

use \app\service\system\RoleService;

class RoleController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = ''; // 框架菜单
        $roleModel = new RoleModel(); // 模型
        $search = array(
            'name'=>''
        ); // 搜索
        $whereMarks = array();
        $whereValues = array();
        $where = array();
        $paginationService = null; // 分页
        $recordTotal = 0; // 总记录
        $paginationNodeIntact = ''; // 节点
        $roles = array();

        // 菜单
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_role');

        // 搜索
        if(isset($_GET['name']) && $_GET['name'] !== ''){
            $whereMarks[] = 'name = :name';
            $whereValues[':name'] = '%'.$_GET['name'].'%';
            $search['name'] = SafeService::entity($_GET['name']);
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        if(!empty($whereMarks)){
            $where['value'] = $whereValues;
        }
        
        $recordTotal = $roleModel->getOne('count(1)', $where);
        
        $paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
        $paginationNodeIntact = $paginationService->getNodeIntact();
        
        $roles = $roleModel->getAll('id, name, time_edit', $where, 'order by id desc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);
        foreach($roles as &$role){
            $role['time_edit_name'] = date('Y-m-d H:i:s', $role['time_edit']);
        }
        
        $roles = SafeService::entity($roles, array('id'));
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('roles', $roles);
        $this->assign('paginationNodeIntact', $paginationNodeIntact);
        $this->display('system/role/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/role/add.php');
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
        $roleModel = new RoleModel();
        
        // 验证
        $validateService->rule = array(
            'name' => 'require|max_length:64'
        );
        $validateService->message = array(
            'name.require' => '请输入角色名称',
            'name.max_length' => '角色名称不能大于64个字',
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 入库
        $data = array(
            'name'=>$_POST['name'],
            'time_add'=>time(),
            'time_edit'=>time()
        );
        try{
            $roleModel->insert($data);
        }catch(Exception $e){
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        $return['status'] = 'success';
        $return['message'] = '添加成功';
        echo json_encode($return);
    }
    
    /**
     * 修改角色
     */
    function edit(){
        $validateService = new ValidateService();
        $roleModel = new RoleModel();
        $role = array();
        
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
        
        $role = $roleModel->getRow('id, name', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        $role = SafeService::entity($role, array('id'));
        
        $this->assign('role', $role);
        $this->display('system/role/edit.php');
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
        $roleModel = new RoleModel();
        $role = array();
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require|number',
            'name' => 'require|max_length:64'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字',
            'name.require' => '请输入角色名称',
            'name.max_length' => '角色名称不能大于64个字'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 本角色
        $role = $roleModel->getRow(
            'id',
            array(
                'mark'=> 'id = :id',
                'value'=> array(
                    ':id'=>$_POST['id']
                )
            )
        );
        if(empty($role)){
            $return['message'] = '角色没有找到';
            echo json_encode($return);
            exit;
        }
        
        // 更新
        $data = array(
            'name'=>$_POST['name'],
            'time_edit'=>time()
        );
        try{
            $roleModel->update($data, array(
                'mark'=>'id = :id',
                'value'=> array(
                    ':id'=>$role['id']
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
        $roleModel = new RoleModel();
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
        
        try{
            $roleModel->delete(
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