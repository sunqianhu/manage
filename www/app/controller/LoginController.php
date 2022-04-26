<?php
/**
 * 登录
 */
namespace app\controller;

use app\service\CaptchaService;
use app\service\AuthService;
use app\model\UserModel;

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
        
        if(empty($_POST['username'])){
            $return['msg'] = '请输入用户名';
            $return['dom'] = '#username';
            echo json_encode($return);
            exit;
        }
        if(empty($_POST['password'])){
            $return['msg'] = '请输入密码';
            $return['dom'] = '#password';
            echo json_encode($return);
            exit;
        }
        if(empty($_POST['captcha'])){
            $return['msg'] = '请输入验证码';
            $return['dom'] = '#captcha';
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