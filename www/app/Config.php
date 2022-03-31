<?php
/**
 * 配置
 */
namespace app;

class Config{
    /**
     * 全部配置
     */
    static function all(){
        return require(dirname(__DIR__).'/config.php');
    }
    
    /**
     * 渲染视图显示
     * @param String $path 视图文件路径
     */
    static function get($key){
        $config = require(dirname(__DIR__).'/config.php');
        return $config[$key];
    }
}

