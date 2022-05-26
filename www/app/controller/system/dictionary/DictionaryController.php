<?php
/**
 * 字典管理
 */
namespace app\controller\system\dictionary;

use \app\controller\BaseController;
use \app\model\system\DictionaryModel;
use \app\service\FrameMainService;
use \app\service\PaginationService;
use \app\service\SafeService;
use \app\service\ValidateService;

use \app\service\system\DictionaryService;

class DictionaryController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $frameMainMenu = ''; // 框架菜单
        $dictionaryModel = new DictionaryModel(); // 模型
        $search = array(
            'type'=>''
        ); // 搜索
        $whereMarks = array();
        $whereValues = array();
        $where = array();
        $paginationService = null; // 分页
        $recordTotal = 0; // 总记录
        $paginationIntactNode = ''; // 节点
        $dictionarys = array();

        // 菜单
        $frameMainMenu = FrameMainService::getPageLeftMenu('system_dictionary');

        // 搜索
        if(isset($_GET['type']) && $_GET['type'] !== ''){
            $whereMarks[] = 'type = :type';
            $whereValues[':type'] = $_GET['type'];
            $search['type'] = SafeService::entity($_GET['type']);
        }
        if(!empty($whereMarks)){
            $where['mark'] = implode(' and ', $whereMarks);
        }
        if(!empty($whereMarks)){
            $where['value'] = $whereValues;
        }
        
        $recordTotal = $dictionaryModel->getOne('count(1)', $where);
        
        $paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
        $paginationIntactNode = $paginationService->getNodeIntact();
        
        $dictionarys = $dictionaryModel->getAll('id, type, `key`, `value`, `sort`', $where, 'order by type asc, `sort` asc, id asc', 'limit '.$paginationService->limitStart.','.$paginationService->pageSize);
        
        $dictionarys = SafeService::entity($dictionarys, array('id'));
        
        // 显示
        $this->assign('frameMainMenu', $frameMainMenu);
        $this->assign('search', $search);
        $this->assign('dictionarys', $dictionarys);
        $this->assign('paginationIntactNode', $paginationIntactNode);
        $this->display('system/dictionary/index.php');
    }
    
    /**
     * 添加
     */
    function add(){
        $this->display('system/dictionary/add.php');
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
        $dictionaryModel = new DictionaryModel();
        
        // 验证
        $validateService->rule = array(
            'type' => 'require|max_length:64',
            'key' => 'require|max_length:64',
            'value' => 'require|max_length:128',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'type.require' => '请输入字典类型',
            'type.max_length' => '字典类型不能大于64个字',
            'key.require' => '请输入字典键',
            'key.max_length' => '字典键不能大于64个字',
            'value.require' => '请输入字典值',
            'value.max_length' => '字典值不能大于128个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 入库
        $data = array(
            'type'=>$_POST['type'],
            'key'=>$_POST['key'],
            'value'=>$_POST['value'],
            'sort'=>$_POST['sort']
        );
        try{
            $dictionaryModel->insert($data);
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
     * 修改字典
     */
    function edit(){
        $validateService = new ValidateService();
        $dictionaryModel = new DictionaryModel();
        $dictionary = array();
        
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
        
        $dictionary = $dictionaryModel->getRow('id, type, `key`, `value`, `sort`', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$_GET['id']
            )
        ));
        $dictionary = SafeService::entity($dictionary, array('id'));
        
        $this->assign('dictionary', $dictionary);
        $this->display('system/dictionary/edit.php');
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
        $dictionaryModel = new DictionaryModel();
        $dictionary = array();
        $data = array();
        
        // 验证
        $validateService->rule = array(
            'id' => 'require|number',
            'type' => 'require|max_length:64',
            'key' => 'require|max_length:64',
            'value' => 'require|max_length:128',
            'sort' => 'number|max_length:10'
        );
        $validateService->message = array(
            'id.require' => 'id参数错误',
            'id.number' => 'id必须是个数字',
            'type.require' => '请输入字典类型',
            'type.max_length' => '字典类型不能大于64个字',
            'key.require' => '请输入字典键',
            'key.max_length' => '字典键不能大于64个字',
            'value.require' => '请输入字典值',
            'value.max_length' => '字典值不能大于128个字',
            'sort.number' => '排序必须是个数字',
            'sort.max_length' => '排序不能大于10个字'
        );
        if(!$validateService->check($_POST)){
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = '#'.$validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 本字典
        $dictionary = $dictionaryModel->getRow(
            'id',
            array(
                'mark'=> 'id = :id',
                'value'=> array(
                    ':id'=>$_POST['id']
                )
            )
        );
        if(empty($dictionary)){
            $return['message'] = '字典没有找到';
            echo json_encode($return);
            exit;
        }
        
        // 更新
        $data = array(
            'type'=>$_POST['type'],
            'key'=>$_POST['key'],
            'value'=>$_POST['value'],
            'sort'=>$_POST['sort']
        );
        try{
            $dictionaryModel->update($data, array(
                'mark'=>'id = :id',
                'value'=> array(
                    ':id'=>$dictionary['id']
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
        $dictionaryModel = new DictionaryModel();
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
            $dictionaryModel->delete(
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