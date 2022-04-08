<?php
/**
 * 配置
 */
namespace service;

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
        $value = '';
        
        if(isset($config[$key])){
            $value = $config[$key];
        }
        
        return $value;
    }
}
