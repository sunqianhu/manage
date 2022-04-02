<?php
/**
 * 基类控制器
 */
namespace app\controller;

use app\View;
use app\service\Auth;

class Base{
    
    /**
     * 构造
     */
    function __construct(){
        $accessController = array(
            'login'
        ); // 公开访问控制器
        
        // 登录
        if(!Auth::isLogin()){
            if(!in_array($_GET['c'], $accessController)){
                header('location:index.php?c=login&a=main');
                exit;
            }
        }
        
        // 权限
        
    }
    
    /**
     * 渲染视图显示
     * @param String $pathFile 视图文件路径
     * @param null $data 渲染的数据
     */
    function display($pathFile, $data = ''){
        $view = new View();
        $view->display($pathFile, $data);
    }
}