<?php
/**
 * 基类控制器
 */
namespace app\controller;

use app\Config;

class Base{
    
    /**
     * 渲染视图显示
     * @param String $pathFile 视图文件路径
     * @param null $data 渲染的数据
     */
    function display($pathFile, $data = ''){
        $config = Config::all();
        $pathFull = '';
        $pathView = $config['view_path'];
        $pathFull = $pathView.$pathFile;
        
        require_once $pathFull;
    }
}