<?php
/**
 * 配置
 */
namespace service;

class View{
    /**
     * 渲染视图显示
     * @param String $pathFile 视图文件路径
     * @param null $data 渲染的数据
     */
    static function display($pathFile, $data = ''){
        $config = Config::all();
        $pathFull = '';
        $pathView = $config['view_path'];
        $pathFull = $pathView.$pathFile;
        
        require_once $pathFull;
    }
}

