<?php
/**
 * 用户管理
 */
namespace app\controller\system\user;

use \app\controller\BaseController;
use \app\model\system\UserModel;
use \app\model\system\DepartmentModel;
use \app\model\system\RoleModel;
use \app\service\FrameMainService;
use \app\service\PaginationService;
use \app\service\SafeService;
use \app\service\ValidateService;
use \app\service\ZtreeService;
use \app\service\ArrayService;
use \app\service\system\UserService;
use \app\service\system\DictionaryService;

class UserController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = ''; // 框架菜单
        $userModel = new UserModel(); // 模型
        $departmentModel = new DepartmentModel();
        
        $whereMarks = array();
        $whereValues = array();
        $where = array();
        
        $paginationService = null; // 分页
        $recordTotal = 0; // 总记录
        $paginationNodeIntact = ''; // 节点
        
        $search = array(
            'department_id'=>0,
            'department_name'=>'不限',
            'name'=>''
        ); // 搜索
        $departments = array();
        $department = ''; // 部门json数据
        $statusOption = '';
        
        $users = array();
        
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_user');
        $statusOption = DictionaryService::getSelectOption('system_user_status', array(@$_GET['status']));

        $departments = $departmentModel->select('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $departments = ZtreeService::setOpenByFirst($departments);
        $department = json_encode($departments);
        if(isset($_GET['department_name'])){
            $search['department_name'] = $_GET['department_name'];
        }
        
        // 搜索
        if(isset($_GET['department_id'])){
            $whereMarks[] = 'department_id = :department_id';
            $whereValues[':department_id'] = $_GET['department_id'];
            $search['department_id'] = $_GET['department_id'];
        }
        if(isset($_GET['name']) && $_GET['name'] !== ''){
            $whereMarks[] = 'name like :name';
            $whereValues[':name'] = '%'.$_GET['name'].'%';
            $search['name'] = $_GET['name'];
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        if(!empty($whereMarks)){
            $where['value'] = $whereValues;
        }
        
        $recordTotal = $userModel->selectOne('count(1)', $where);
        
        $paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
        $paginationNodeIntact = $paginationService->getNodeIntact();
        
        $users = $userModel->select('id, username, `name`, `time_login`, time_edit, phone, status, department_id', $where, 'order by id asc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);
        foreach($users as &$user){
            $user['department_name'] = $departmentModel->selectOne('name', array(
                'mark'=>'id = :id',
                'value'=>array(
                    ':id'=>$user['department_id']
                )
            ));
            $user['status_name'] = DictionaryService::getValue('system_user_status', $user['status']);
            $user['status_style_class'] = $user['status'] == 2 ? 'sun_badge sun_badge_orange': 'sun_badge';
            $user['time_edit_name'] = $user['time_edit'] ? date('Y-m-d H:i:s', $user['time_edit']) : '-';
            $user['time_login_name'] = $user['time_login'] ? date('Y-m-d H:i:s', $user['time_login']) : '-';
        }
        $users = SafeService::frontDisplay($users, array('id'));
        $search = SafeService::frontDisplay($search);
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('department', $department);
        $this->assign('statusOption', $statusOption);
        $this->assign('users', $users);
        $this->assign('paginationNodeIntact', $paginationNodeIntact);
        $this->display('system/user/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $nodeStatus = DictionaryService::getRadio('system_user_status', 'status', 1);
        
        $this->assign('nodeStatus', $nodeStatus);
        $this->display('system/user/add.php');
    }
    
    /**
     * 添加选择部门
     */
    function addSelectDepartment(){
        $departmentModel = new DepartmentModel();
        $departments = array();
        $department = ''; // 部门json数据
        
        $departments = $departmentModel->select('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $departments = ZtreeService::setOpenByFirst($departments);
        $department = json_encode($departments);
        
        $this->assign('department', $department);
        $this->display('system/user/add_select_department.php');
    }
    
    /**
     * 修改选择部门
     */
    function editSelectDepartment(){
        $departmentModel = new DepartmentModel();
        $departments = array();
        $department = ''; // 部门json数据
        
        $departments = $departmentModel->select('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $departments = ZtreeService::setOpenByFirst($departments);
        $department = json_encode($departments);
        
        $this->assign('department', $department);
        $this->display('system/user/edit_select_department.php');
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
        $userModel = new UserModel();
        $user = array();
        
        // 验证
        $validateService->rule = array(
            'username' => 'require|max_length:64',
            'status' => 'require|number',
            'password' => 'require|min_length:8',
            'name' => 'require|max_length:32',
            'phone' => 'require|number|min_length:11|max_length:11',
            'department_id' => 'require:^0|number',
            'role_ids' => 'require|number_array'
        );
        $validateService->message = array(
            'username.require' => '请输入用户名',
            'username.max_length' => '用户名不能大于64个字',
            'password.require' => '请输入密码',
            'password.min_length' => '密码不能小于8个字符',
            'name.require' => '请输入姓名',
            'name.max_length' => '姓名不能大于32个字',
            'phone.require' => '请输入手机号码',
            'phone.number' => '手机号码只能是数字',
            'phone.max_length' => '手机号码只能11位',
            'phone.min_length' => '手机号码只能11位',
            'department_id.require' => '请选择部门',
            'department_id.number' => '部门参数必须是个数字',
            'role_ids.require' => '请选择角色',
            'role_ids.number_array' => '角色参数错误'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        if($_POST['password'] != $_POST['password2']){
            $return['message'] = '两次输入密码不相同';
            $return['data']['dom'] = '#pasword';
            echo json_encode($return);
            exit;
        }
        
        $_POST['role_id_string'] = implode(',', $_POST['role_ids']);
        
        $user = $userModel->selectRow('id', array(
            'mark'=>'username = :username',
            'value'=>array(
                ':username'=>$_POST['username']
            )
        ));
        if(!empty($user)){
            $return['message'] = '用户名已经存在';
            $return['data']['dom'] = '#username';
            echo json_encode($return);
            exit;
        }
        
        // 入库
        $data = array(
            'username'=>$_POST['username'],
            'status'=>$_POST['status'],
            'password'=>md5($_POST['password']),
            'name'=>$_POST['name'],
            'phone'=>$_POST['phone'],
            'department_id'=>$_POST['department_id'],
            'role_id_string'=>$_POST['role_id_string'],
            'time_add'=>time()
        );
        try{
            $userModel->insert($data);
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
     * 修改用户
     */
    function edit(){
        $validateService = new ValidateService();
        $userModel = new UserModel();
        $departmentModel = new DepartmentModel();
        $roleModel = new RoleModel();
        $user = array();
        $roles = array();
        $nodeStatus = '';
        $nodeRole = '';
        
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
        
        $user = $userModel->selectRow('id, username, `name`, `phone`, `status`, department_id, role_id_string', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        if(empty($user)){
            header('location:../../error.html?message='.urlencode('没有找到用户'));
            exit;
        }
        
        $user['role_ids'] = explode(',', $user['role_id_string']);
        $user['department_name'] = $departmentModel->selectOne('name', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$user['department_id']
            )
        ));
        $user = SafeService::frontDisplay($user, array('id'));
        $nodeStatus = DictionaryService::getRadio('system_user_status', 'status', $user['status']);
        
        $roles = $roleModel->select('id, name', array());
        $nodeRole = ArrayService::getSelectOption($roles, $user['role_ids'], 'id', 'name');
        
        $this->assign('user', $user);
        $this->assign('nodeStatus', $nodeStatus);
        $this->assign('nodeRole', $nodeRole);
        $this->display('system/user/edit.php');
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
        $userModel = new UserModel();
        $user = array();
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require|number',
            'status' => 'require|number',
            'name' => 'require|max_length:32',
            'phone' => 'require|number|min_length:11|max_length:11',
            'department_id' => 'require|number',
            'role_ids' => 'require|number_array'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字',
            'name.require' => '请输入姓名',
            'name.max_length' => '姓名不能大于32个字',
            'phone.require' => '请输入手机号码',
            'phone.number' => '手机号码只能是数字',
            'phone.max_length' => '手机号码只能11位',
            'phone.min_length' => '手机号码只能11位',
            'department_id.require' => '请选择部门',
            'department_id.number' => '部门参数必须是个数字',
            'role_ids.require' => '请选择角色',
            'role_ids.number_array' => '角色参数错误'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        $_POST['role_id_string'] = implode(',', $_POST['role_ids']);
        
        // 本用户
        $user = $userModel->selectRow('id', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_POST['id']
            )
        ));
        if(empty($user)){
            $return['message'] = '用户没有找到';
            echo json_encode($return);
            exit;
        }
        
        // 更新
        $data = array(
            'status'=>$_POST['status'],
            'name'=>$_POST['name'],
            'phone'=>$_POST['phone'],
            'department_id'=>$_POST['department_id'],
            'role_id_string'=>$_POST['role_id_string'],
            'time_edit'=>time()
        );
        if($_POST['password'] !== ''){
            $data['password'] = md5($_POST['password']);
        }
        try{
            $userModel->update($data, array(
                'mark'=>'id = :id',
                'value'=> array(
                    ':id'=>$user['id']
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
        $userModel = new UserModel();
        $validateService = new ValidateService();
        $user = array();
        
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
        
        // 本用户
        $user = $userModel->selectRow(
            'id',
            array(
                'mark'=> 'id = :id',
                'value'=> array(
                    ':id'=>$_GET['id']
                )
            )
        );
        if(empty($user)){
            $return['message'] = '用户没有找到';
            echo json_encode($return);
            exit;
        }
        
        try{
            $userModel->delete(
                array(
                    'mark'=>'id = :id',
                    'value'=> array(
                        ':id'=>$user['id']
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