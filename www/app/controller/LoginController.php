<?php
/**
 * 登录
 */
namespace app\controller;

use app\service\CaptchaService;
use app\service\AuthService;
use app\model\system\UserModel;
use app\service\ValidateService;
use app\service\ResponseService;

class LoginController extends BaseController{
    /**
     * 入口
     */
    function index(){
        $this->display('login.php');
    }
    
    /**
     * 验证码
     */
    function captcha(){
        CaptchaService::create('captcha_login');
    }
    
    /**
     * 登录处理
     */
    function login(){
        $userModel = null;
        $validateService = new ValidateService();
        
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
            echo ResponseService::json('error', $validateService->getErrorMessage(), array('dom'=>$validateService->getErrorField()));
            exit;
        }
        
        // 验证码
        if(empty($_SESSION['captcha_login'])){
            echo ResponseService::json('error', '请重新获取验证码', array('dom'=>'#captcha', 'captcha'=>1));
            exit;
        }
        if($_SESSION['captcha_login'] != $_POST['captcha']){
            echo ResponseService::json('error', '验证码错误', array('dom'=>'#captcha', 'captcha'=>1));
            exit;
        }
        unset($_SESSION['captcha_login']);
        
        $userModel = new UserModel();
        try{
            $user = $userModel->getRow(
                'id, username, name', 
                array(
                    'mark'=>'username = :username and password = :password',
                    'value'=>array(
                        ':username'=>$_POST['username'],
                        ':password'=>md5($_POST['password'])
                    )
                )
            );
        }catch(\Exception $e){
            echo ResponseService::json('error', '验证码错误', array('captcha'=>1));
            exit;
        }
        
        if(empty($user)){
            echo ResponseService::json('error', '用户名或密码错误', array('captcha'=>1));
            exit;
        }
        
        // 服务层
        AuthService::saveToken($user);
        
        echo ResponseService::json('success', '登录成功', array('captcha'=>1));
    }
    
    /**
     * 退出登录
     */
    function exit(){
        AuthService::unsetToken();
        header('location:/login/index');
    }
}