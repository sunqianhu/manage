<?php
/**
 * 菜单管理
 */
namespace app\controller\system\menu;

use \app\controller\BaseController;
use \app\model\system\MenuModel;
use \app\service\ValidateService;
use \app\service\ZtreeService;
use \app\service\TreeService;
use \app\service\SafeService;
use \app\service\system\MenuService;

class MenuController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $menuModel = new MenuModel();
        $menus = array(); // 菜单数据
        $menuNode = ''; // 菜单表格节点
        $frameMainMenu = '';
        $search = array(
            'id'=>'',
            'name'=>''
        );
        $whereMarks = array();
        $whereValues = array();
        $where = array();
        
        // 菜单
        $frameMainMenu = MenuService::getFrameMainNode('system_menu');
        
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
        $menus = $menuModel->getAll('id, parent_id, name, `sort`', $where, 'order by `sort` asc, id asc');
        $menus = TreeService::getDataTree($menus, 'child', 'id', 'parent_id');
        $menus = TreeService::addLevel($menus, 1);
        $menus = SafeService::entity($menus, array('id', 'parent_id'));
        $menuNode = MenuService::getIndexTreeNode($menus);
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('menuNode', $menuNode);
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
        $menuModel = new menuModel();
        $menus = array();
        $menu = ''; // 部门json数据
        
        $menus = $menuModel->getAll('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $menus = ZtreeService::setOpenByFirst($menus);
        $menu = json_encode($menus);
        
        $this->assign('menu', $menu);
        $this->display('system/menu/add_select_menu.php');
    }
    
    /**
     * 添加部门保存
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
        $menuModel = new menuModel();
        $menuParent = array(); // 上级部门
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
        $menuParent = $menuModel->getRow(
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
            $id = $menuModel->insert($data);
        }catch(Exception $e){
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        $parentIds = $menuParent['parent_ids'].','.$id;
        $menuModel->update(
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
    
    
}