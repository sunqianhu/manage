<?php
/**
 * 配置
 */
namespace app;

class Config{
    static $config = array();
    
    /**
     * 全部配置
     */
    static function all(){
        if(empty(self::$config)){
            self::$config = require_once dirname(__DIR__).'/config.php';
        }
        
        return self::$config;
    }
    
    /**
     * 得到一个配置
     * @param String $key 配置key
     */
    static function get($key){
        $value = '';
        
        if(empty(self::$config)){
            self::$config = require_once dirname(__DIR__).'/config.php';
        }
        
        if(isset(self::$config[$key])){
            $value = self::$config[$key];
        }
        
        return $value;
    }
}

