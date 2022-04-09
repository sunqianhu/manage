<?php
/**
 * 配置
 */
namespace app;

class Config{
    /**
     * 得到全部配置
     */
    static function all(){
        return require_once dirname(__DIR__).'/config.php';
    }
    
    /**
     * 得到一个配置项
     * @param String $key 配置项key
     */
    static function get($key){
        $config = require_once dirname(__DIR__).'/config.php';
        $value = '';
        
        if(isset($config[$key])){
            $value = $config[$key];
        }
        
        return $value;
    }
}

