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
use \app\service\FrameMainService;
use \app\service\system\MenuService;
use \app\service\system\DictionaryService;

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
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_menu');
        
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
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        $where['value'] = $whereValues;
        
        // 数据
        $menus = $menuModel->getAll('id, parent_id, name, `sort`', $where, 'order by `sort` asc, id asc');
        $menus = TreeService::getDataTree($menus, 'child', 'id', 'parent_id');
        $menus = TreeService::addLevel($menus, 1);
        $menus = SafeService::frontDisplay($menus, array('id', 'parent_id'));
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
        $menuTypeRadioNode = DictionaryService::getRadio('system_menu_type', 'type', 1);
        
        $this->assign('menuTypeRadioNode', $menuTypeRadioNode);
        $this->display('system/menu/add.php');
    }
    
    /**
     * 添加选择菜单
     */
    function addSelectMenu(){
        $menuModel = new menuModel();
        $menus = array();
        $menu = ''; // 菜单json数据
        
        $menus = $menuModel->getAll('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $menus = ZtreeService::setOpenByFirst($menus);
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
            'data'=>array(
                'dom'=>''
            )
        ); // 返回数据
        $validateService = new ValidateService();
        $menuModel = new menuModel();
        $menuParent = array(); // 上级菜单
        $id = 0; // 添加菜单id
        $parentIds = ''; // 所有上级菜单id
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'parent_id' => 'number',
            'type' => 'require',
            'name' => 'require|max_length:32',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'parent_id.number' => '请选择上级菜单',
            'type.require' => '请选择菜单类型',
            'name.require' => '请输入菜单名称',
            'name.max_length' => '菜单名称不能大于32个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 上级菜单
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
            'type'=>$_POST['type'],
            'name'=>$_POST['name'],
            'url'=>$_POST['url'],
            'sort'=>$_POST['sort']
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
    
    /**
     * 修改
     */
    function edit(){
        $validateService = new ValidateService();
        $menuModel = new MenuModel();
        $menu = array();
        
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
        
        $menu = $menuModel->getRow('id, parent_id, type, name, url, `sort`', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        $menu['parent_name'] = $menuModel->getOne('name', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=> $menu['parent_id']
            )
        ));
        $menu = SafeService::frontDisplay($menu, array('id', 'type'));
        
        $menuTypeRadioNode = DictionaryService::getRadio('system_menu_type', 'type', $menu['type']);
        
        $this->assign('menu', $menu);
        $this->assign('menuTypeRadioNode', $menuTypeRadioNode);
        $this->display('system/menu/edit.php');
    }
    
    /**
     * 修改选择菜单
     */
    function editSelectMenu(){
        $menuModel = new MenuModel();
        $menus = array(); // 菜单数据
        $menu = ''; // 菜单json数据
        
        $menus = $menuModel->getAll('id, name, parent_id', array(), 'order by parent_id asc, id asc');
        $menus = ZtreeService::setOpenByFirst($menus);
        $menu = json_encode($menus);
        
        $this->assign('menu', $menu);
        $this->display('system/menu/edit_select_menu.php');
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
        $menuModel = new MenuModel();
        $menuCurrent = array(); // 本菜单
        $menuParent = array(); // 上级菜单
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require|number',
            'parent_id' => 'number',
            'type' => 'require',
            'name' => 'require|max_length:32',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字',
            'parent_id.number' => '请选择上级菜单',
            'type.require' => '请选择菜单类型',
            'name.require' => '请输入菜单名称',
            'name.max_length' => '菜单名称不能大于32个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 本菜单
        $menuCurrent = $menuModel->getRow(
            'id, parent_id',
            array(
                'mark'=> 'id = :id',
                'value'=> array(
                    ':id'=>$_POST['id']
                )
            )
        );
        if(empty($menuCurrent)){
            $return['message'] = '此菜单没有找到';
            echo json_encode($return);
            exit;
        }
        
        // 上级菜单
        $menuParent = $menuModel->getRow(
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
            'parent_ids'=>$menuParent['parent_ids'].','.$menuCurrent['id'],
            'name'=>$_POST['name'],
            'type'=>$_POST['type'],
            'url'=>$_POST['url'],
            'sort'=>$_POST['sort']
        );
        try{
            $id = $menuModel->update($data, array(
                'mark'=>'id = :id',
                'value'=> array(
                    ':id'=>$menuCurrent['id']
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
        $menuChild = array();
        $menuModel = new MenuModel();
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
            $return['message'] = '不能删除根菜单';
            echo json_encode($return);
            exit;
        }
        
        $menuChild = $menuModel->getRow(
            'id',
            array(
                'mark'=>'parent_id = :id',
                'value'=> array(
                    ':id'=>$_GET['id']
                )
            )
        );
        if(!empty($menuChild)){
            $return['message'] = '该菜单存在下级菜单';
            echo json_encode($return);
            exit;
        }
        
        try{
            $menuModel->delete(
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