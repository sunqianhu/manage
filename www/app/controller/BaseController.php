<?php
/**
 * 基类控制器
 */
namespace app\controller;

use app\Route;
use app\Config;
use app\service\AuthService;

class BaseController{
    public $viewDatas = array(); // 视图数据
    
    /**
     * 构造
     */
    function __construct(){
        $accessControllerPublics = array(
            'LoginController'
        ); // 公开访问控制器
        $config = Config::all();
        /*
        // 登录
        if(!AuthService::isLogin()){
            if(!in_array(Route::$controller, $accessControllerPublics)){
                header('location:'.$config['app_domain'].'/login/index');
                exit;
            }
        }
        */
        // 权限
        
        // 配置
        $this->assign('config', $config);
    }
    
    /**
     * 视图分配值
     * @param String $key 变量名
     * @param null $value 变量值
     */
    function assign($key, $value){
        $this->viewDatas[$key] = $value;
    }
    
    /**
     * 渲染视图显示
     * @param String $file 视图文件路径
     */
    function display($file){
        $dir = Config::get('view_dir');
        $path = '';
        
        extract($this->viewDatas);
        $path = $dir.$file;
        
        require_once $path;
    }
}