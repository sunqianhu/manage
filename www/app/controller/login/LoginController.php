<?php
/**
 * 登录
 */
namespace app\controller\login;

use \app\controller\BaseController;
use \app\service\CaptchaService;
use \app\service\AuthService;
use \app\model\system\UserModel;
use \app\service\ValidateService;

class LoginController extends BaseController{
    /**
     * 入口
     */
    function index(){
        $this->display('login/index.php');
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
            'message'=>'',
            'data'=>array(
                'dom'=>'',
                'captcha'=>'0'
            )
        );
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
            $return['message'] = $validateService->getErrorMessage();
            $return['data']['dom'] = $validateService->getErrorField();
            echo json_encode($return);
            exit;
        }
        
        // 验证码
        if(empty($_SESSION['captcha_login'])){
            $return['message'] = '请重新获取验证码';
            $return['data']['dom'] = '#captcha';
            $return['data']['captcha'] = '1';
            echo json_encode($return);
            exit;
        }
        if($_SESSION['captcha_login'] != $_POST['captcha']){
            $return['message'] = '验证码错误';
            $return['data']['dom'] = '#captcha';
            $return['data']['captcha'] = '1';
            echo json_encode($return);
            exit;
        }
        unset($_SESSION['captcha_login']);
        $return['data']['captcha'] = '1';
        
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
            $return['message'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        if(empty($user)){
            $return['message'] = '用户名或密码错误';
            echo json_encode($return);
            exit;
        }
        
        // 服务层
        AuthService::saveToken($user);
        
        $return['status'] = 'success';
        $return['message'] = '登录成功';
        echo json_encode($return);
    }
    
    /**
     * 退出登录
     */
    function exit(){
        AuthService::unsetToken();
        header('location:login.html');
    }
}