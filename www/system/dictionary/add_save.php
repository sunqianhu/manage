<?php
/**
 * 添加保存
 */
require_once '../../library/session.php';
require_once '../../library/autoload.php';

use library\model\system\DictionaryModel;
use library\service\ValidateService;
use library\service\AuthService;

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
if(!AuthService::isLogin()){
    $return['message'] = '登录已失效';
    echo json_encode($return);
    exit;
}
if(!AuthService::isPermission('system_dictionary')){
    $return['message'] = '无权限';
    echo json_encode($return);
    exit;
}

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
?>