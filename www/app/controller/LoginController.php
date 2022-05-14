<?php
/**
 * 登录
 */
namespace app\controller;

use app\service\CaptchaService;
use app\service\AuthService;
use app\model\system\UserModel;
use app\service\ValidateService;

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
        $return = array(
            'status'=>'error',
            'msg'=>'',
            'dom'=>'',
            'captcha'=>'0'
        );
        $user = null;
        $validateService = new ValidateService();
        
        // 验证
        $validateService->rule = array(
            'username' => 'require',
            'password' => 'require',
            'captcha' => 'require|max_length:6'
        );
        $validateService->message = array(
            'username.require' => '请输入用户名',
            'password.require' => '请输入密码',
            'captcha.require' => '请输入验证码',
            'captcha.max_length' => '验证码长度不能大于6个字符'
        );
        if(!$validateService->check($_POST)){
            $return['msg'] = $validateService->getErrorMessage();
            $return['dom'] = $validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        if(empty($_SESSION['captcha_login'])){
            $return['msg'] = '请重新获取验证码';
            $return['dom'] = '#captcha';
            $return['captcha'] = '1';
            echo json_encode($return);
            exit;
        }
        if($_SESSION['captcha_login'] != $_POST['captcha']){
            $return['msg'] = '验证码错误';
            $return['dom'] = '#captcha';
            $return['captcha'] = '1';
            echo json_encode($return);
            exit;
        }
        
        $userModel = new UserModel();
        try{
            $user = $userModel->getRowByUsernamePassword($_POST['username'], $_POST['password']);
        }catch(\Exception $e){
            $return['msg'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        // 服务层
        AuthService::saveToken($user);
        
        $return['status'] = 'success';
        $return['msg'] = '登录成功';
        echo json_encode($return);
    }
    
    /**
     * 退出登录
     */
    function exit(){
        AuthService::unsetToken();
        header('location:/login/index');
    }
}