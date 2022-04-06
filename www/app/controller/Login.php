<?php
/**
 * 登录
 */
namespace app\controller;

use app\Exception;
use app\helper\Captcha;
use app\model\Admin;
use app\service\Auth;

class Login extends Base{
    /**
     * 入口
     */
    function main(){
        $this->display("login.php");
    }
    
    /**
     * 验证码
     */
    function captcha(){
        Captcha::createImage('captcha_login');
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
        
        $admin = new Admin();
        try{
            $admin->getRowByUsernamePassword($_POST['username'], $_POST['password']);
        }catch(Exception $e){
            $return['msg'] = $e->getMessage();
            echo json_encode($return);
            exit;
        }
        
        // 服务层
        Auth::saveSessionAdmin($admin);
        
        $return['status'] = 'success';
        $return['msg'] = '登录成功';
        echo json_encode($return);
    }
}