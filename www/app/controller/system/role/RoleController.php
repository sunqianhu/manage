<?php
/**
 * 角色管理
 */
namespace app\controller\system\role;

use \app\controller\BaseController;
use \app\model\system\RoleModel;
use \app\model\system\RoleMenuModel;
use \app\model\system\MenuModel;
use \app\service\FrameMainService;
use \app\service\PaginationService;
use \app\service\SafeService;
use \app\service\ValidateService;
use \app\service\ZtreeService;
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
            $search['name'] = $_GET['name'];
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        if(!empty($whereMarks)){
            $where['value'] = $whereValues;
        }
        $recordTotal = $roleModel->selectOne('count(1)', $where);
        
        $paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
        $paginationNodeIntact = $paginationService->getNodeIntact();
        
        $roles = $roleModel->select('id, name, time_edit', $where, 'order by id desc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);
        foreach($roles as &$role){
            $role['time_edit_name'] = date('Y-m-d H:i:s', $role['time_edit']);
        }
        
        $search = SafeService::frontDisplay($search);
        $roles = SafeService::frontDisplay($roles, array('id'));
        
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
        $menuModel = new menuModel();
        $menus = array();
        $menu = ''; // 菜单json数据
        
        $menus = $menuModel->select('id, name, parent_id', array(
            'mark'=>'parent_id != 0'
        ), 'order by parent_id asc, id asc');
        $menus = ZtreeService::setOpenByFirst($menus);
        $menu = json_encode($menus);
        
        $this->assign('menu', $menu);
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
        $roleMenuModel = new RoleMenuModel();
        $roleId = 0; // 角色id
        $menuIds = array();
        
        // 验证
        $validateService->rule = array(
            'name' => 'require|max_length:64',
            'remark' => 'max_length:255',
            'menu_ids' => 'require|number_string:,'
        );
        $validateService->message = array(
            'name.require' => '请输入角色名称',
            'name.max_length' => '角色名称不能大于64个字',
            'remark.max_length' => '角色名称不能大于255个字',
            'menu_ids.require' => '请选择菜单权限',
            'menu_ids.number_string' => '菜单权限参数错误'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        $menuIds = explode(',', $_POST['menu_ids']);
        
        // 入库
        $data = array(
            'name'=>$_POST['name'],
            'remark'=>$_POST['remark'],
            'time_add'=>time(),
            'time_edit'=>time()
        );
        try{
            $roleId = $roleModel->insert($data);
        }catch(Exception $e){
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        // 关联
        $roleMenuModel->delete(array(
            'mark'=>'role_id = :role_id',
            'value'=>array(
                ':role_id'=>$roleId
            )
        ));
        foreach($menuIds as $menuId){
            $data = array(
                'role_id'=>$roleId,
                'menu_id'=>$menuId
            );
            $roleMenuModel->insert($data);
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
        $roleMenuModel = new RoleMenuModel();
        $menuModel = new menuModel();
        $role = array();
        $roleMenus = array();
        $roleMenuIds = array();
        $menus = array();
        $menu = ''; // 菜单json数据
        
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
        
        $role = $roleModel->selectRow('id, name, remark', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        if(empty($role)){
            header('location:../../error.html?message='.urlencode('id参数错误'));
            exit;
        }
        
        $roleMenus = $roleMenuModel->select('menu_id', array(
            'mark'=>'role_id = :role_id',
            'value'=>array(
                ':role_id'=>$role['id']
            )
        ));
        $roleMenuIds = array_column($roleMenus, 'menu_id');
        $role['menu_ids'] = implode(',', $roleMenuIds);
        $role = SafeService::frontDisplay($role, array('id'));
        
        $menus = $menuModel->select('id, name, parent_id', array(
            'mark'=>'parent_id != 0'
        ), 'order by parent_id asc, id asc');
        $menus = ZtreeService::setOpenByFirst($menus);
        $menus = ZtreeService::setChecked($menus, $roleMenuIds);
        $menu = json_encode($menus);
        
        $this->assign('role', $role);
        $this->assign('menu', $menu);
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
        $roleMenuModel = new RoleMenuModel();
        $role = array();
        $data = array();
        $menuIds = array();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require|number',
            'name' => 'require|max_length:64',
            'remark' => 'max_length:255',
            'menu_ids' => 'require|number_string:,'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字',
            'name.require' => '请输入角色名称',
            'name.max_length' => '角色名称不能大于64个字',
            'remark.max_length' => '角色名称不能大于255个字',
            'menu_ids.require' => '请选择菜单权限',
            'menu_ids.number_string' => '菜单权限参数错误'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        $menuIds = explode(',', $_POST['menu_ids']);
        
        // 本角色
        $role = $roleModel->selectRow(
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
            'remark'=>$_POST['remark'],
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
        
        // 关联
        $roleMenuModel->delete(array(
            'mark'=>'role_id = :role_id',
            'value'=>array(
                ':role_id'=>$role['id']
            )
        ));
        foreach($menuIds as $menuId){
            $data = array(
                'role_id'=>$role['id'],
                'menu_id'=>$menuId
            );
            $roleMenuModel->insert($data);
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
        $roleMenuModel = new RoleMenuModel();
        $validateService = new ValidateService();
        $role = array();
        
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
        
        $role = $roleModel->selectRow('id', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        if(empty($role)){
            $return['message'] = '角色没有找到';
            echo json_encode($return);
            exit;
        }
        
        try{
            $roleModel->delete(
                array(
                    'mark'=>'id = :id',
                    'value'=> array(
                        ':id'=>$role['id']
                    )
                )
            );
        }catch(Exception $e){
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        $roleMenuModel->delete(
            array(
                'mark'=>'role_id = :role_id',
                'value'=> array(
                    ':role_id'=>$role['id']
                )
            )
        );
        
        $return['status'] = 'success';
        $return['message'] = '删除成功';
        echo json_encode($return);
    }
}