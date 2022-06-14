<?php
/**
 * 登录
 */
require_once '../library/session.php';
require_once '../library/app.php';

use library\model\system\UserModel;
use library\model\system\DepartmentModel;
use library\model\system\MenuModel;
use library\model\system\LoginLogModel;
use library\service\ConfigService;
use library\service\ValidateService;
use library\service\AuthService;
use library\service\IpService;
use library\service\system\UserService;
use library\service\system\DictionaryService;

$return = array(
    'status'=>'error',
    'message'=>'',
    'data'=>array(
        'dom'=>'',
        'captcha'=>'0'
    )
);
$userModel = new UserModel();
$departmentModel = new DepartmentModel();
$menuModel = new MenuModel();
$loginLogModel = new LoginLogModel();
$validateService = new ValidateService();
$user = array();
$department = array();
$roleIdString = ''; // 角色id
$menus = array(); // 菜单
$data = array(); // 数据
$ip = '';

// 验证
$validateService->rule = array(
    'username' => 'require|max_length:64',
    'password' => 'require',
    'captcha' => 'require|max_length:6'
);
$validateService->message = array(
    'username.require' => '请输入用户名',
    'username.max_length' => '用户名不能超过64个字',
    'password.require' => '请输入密码',
    'captcha.require' => '请输入验证码',
    'captcha.max_length' => '验证码长度不能大于6个字符'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = $validateService->getErrorField();
    echo json_encode($return);
    exit;
}

// 验证码
if(empty($_SESSION['login_captcha'])){
    $return['message'] = '请重新获取验证码';
    $return['data']['dom'] = '#captcha';
    $return['data']['captcha'] = '1';
    echo json_encode($return);
    exit;
}
if($_SESSION['login_captcha'] != $_POST['captcha']){
    $return['message'] = '验证码错误';
    $return['data']['dom'] = '#captcha';
    $return['data']['captcha'] = '1';
    echo json_encode($return);
    exit;
}
unset($_SESSION['login_captcha']);
$return['data']['captcha'] = '1';

// 用户
$user = $userModel->selectRow(
    'id, username, name, department_id, role_id_string, head, status, time_login, ip', 
    array(
        'mark'=>'username = :username and password = :password',
        'value'=>array(
            ':username'=>$_POST['username'],
            ':password'=>md5($_POST['password'])
        )
    )
);
if(empty($user)){
    $return['message'] = '用户名或密码错误';
    echo json_encode($return);
    exit;
}
if($user['status'] != 1){
    $return['message'] = DictionaryService::getValue('system_user_status', $user['status']);
    echo json_encode($return);
    exit;
}
$user['head_url'] = UserService::getHeadUrl($user['head']);

// 部门
$department = $departmentModel->selectRow(
    'id, name', 
    array(
        'mark'=>'id = :id',
        'value'=>array(
            ':id'=>$user['department_id']
        )
    )
);
if(empty($department)){
    $return['message'] = '用户还没有设置部门';
    echo json_encode($return);
    exit;
}

// 菜单
$menus = $menuModel->select("id, parent_id, type, name, tag, permission, icon_class, url", array(
    'mark'=>'id in (select menu_id from role_menu where role_id in (:role_id))',
    'value'=>array(
        ':role_id'=> $user['role_id_string']
    )
), 'order by `sort` asc');

// 记录
$ip = IpService::getIp();
$userModel->update(array(
    'time_login'=>time(),
    'ip'=>$ip
), array(
    'mark'=>'id = :id',
    'value'=>array(
        ':id'=>$user['id']
    )
));

// 日志
$data = array(
    'user_id'=>$user['id'],
    'department_id'=>$department['id'],
    'time_login'=>time(),
    'ip'=>$ip
);
$loginLogModel->insert($data);

// 服务层
$_SESSION['user'] = $user;
$_SESSION['department'] = $department;
$_SESSION['menu'] = $menus;

$return['status'] = 'success';
$return['message'] = '登录成功';
echo json_encode($return);
?>