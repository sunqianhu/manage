<?php
/**
 * 部门管理
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\service\MenuService;
use app\model\system\departmentModel;
use app\service\ValidateService;

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
    
    /**
     * 添加部门保存
     */
    function addSave(){
        $return = array(
            'status'=>'error',
            'msg'=>'',
            'dom'=>''
        );
        $validateService = new ValidateService();
        $departmentModel = new departmentModel();
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'parent_id' => 'number',
            'name' => 'require|max_length:25',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'parent_id.number' => '请选择父级部门',
            'name.require' => '请输入部门名称',
            'name.max_length' => '部门名称不能大于32个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['msg'] = $validateService->getErrorMessage();
            $return['dom'] = $validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 入库
        $data = array(
            'parent_id'=>$_POST['parent_id'],
            'name'=>$_POST['name'],
            'sort'=>$_POST['sort'],
            'remark'=>$_POST['remark'],
            'time_add'=>time(),
            'time_update'=>time()
        );
        try{
            $departmentModel->insert($data);
        }catch(Exception $e){
            $return['msg'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        $return['status'] = 'success';
        $return['msg'] = '添加成功';
        echo json_encode($return);
    }
}