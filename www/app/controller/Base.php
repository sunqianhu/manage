<?php
/**
 * 基类控制器
 */
namespace app\controller;

use app\Config;

class Base{
    
    /**
     * 渲染视图显示
     * @param String $filePath 视图文件路径
     * @param null $data 渲染的数据
     */
    static function display($filePath, $data){
        $path = '';
        $viewPath = Config::get("view_path");
        $path = $viewPath.$filePath;
        
        require_once $path;
    }
}