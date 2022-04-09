<?php
/**
 * 基类控制器
 */
namespace app\controller;

use app\Route;
use app\Config;
use app\service\Auth;

class Base{
    public $viewDatas = array(); // 视图数据
    
    
    /**
     * 构造
     */
    function __construct(){
        $accessControllerPublics = array(
            'Login'
        ); // 公开访问控制器
        $config = Config::all();
        
        // 登录
        if(!Auth::isLogin()){
            if(!in_array(Route::$controller, $accessControllerPublics)){
                header('location:'.$config['site_domain'].'/login/index');
                exit;
            }
        }
        
        // 权限
        
        // 配置
        $this->viewDatas['config'] = $config;
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
        $path = Config::get('view_path');
        
        extract($this->viewDatas);
        $path = $path.$file;
        
        require_once $path;
    }
}