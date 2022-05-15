<?php
/**
 * 菜单管理
 */
namespace app\controller\system;

use app\controller\BaseController;
use app\service\MenuService;
use app\mode\system\MenuModel;

class MenuController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = MenuService::getFrameMainHtml('system_menu');
        
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->display('system/menu/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/menu/add.php');
    }
    
    /**
     * 添加选择菜单
     */
    function addSelectMenu(){
        $menuModel = new MenuModel();
        $menus = array();
        $menu = ''; // 菜单json数据
        
        $menus = $menuModel->getAll('id, name, parent_id', array(), 'order by id asc');
        $menus = ZtreeService::setOpenByLevel($menus, 1);
        $menu = json_encode($menus);
        $this->assign('menu', $menu);
        
        $this->display('system/menu/add_select_menu.php');
    }
    
    /**
     * 添加保存
     */
    function addSave(){
        $return = array(
            'status'=>'error',
            'msg'=>'',
            'dom'=>''
        );
        $validateService = new ValidateService();
        $menuModel = new MenuModel();
        $menuParent = array(); // 上级菜单
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'parent_id' => 'number',
            'name' => 'require|max_length:25',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'parent_id.number' => '请选择上级菜单',
            'name.require' => '请输入菜单名称',
            'name.max_length' => '菜单名称不能大于32个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['msg'] = $validateService->getErrorMessage();
            $return['dom'] = $validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 上级菜单
        $menuParent = $menuModel->getRow(
            'level',
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
            'level'=>$menuParent['level'] + 1
        );
        try{
            $menuModel->insert($data);
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