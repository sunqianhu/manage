<?php
/**
 * 视图类
 */
namespace app;

class View{
    /**
     * 渲染视图显示
     * @param String $path 视图文件路径
     */
    static function display($path){
        require_once Config::get("view_path").$path;
    }
}

