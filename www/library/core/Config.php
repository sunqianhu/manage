<?php
/**
 * 配置
 */
namespace library\core;

class Config{
    static $config = array();
    
    /**
     * 得到全部配置
     */
    static function getAll(){
        if(empty(self::$config)){
            self::$config = require_once dirname(dirname(__DIR__)).'/config.php';
        }
        
        return self::$config;
    }
    
    /**
     * 得到一个配置
     * @param string $key 配置key
     */
    static function getOne($key){
        $value = '';
        
        if(empty(self::$config)){
            self::$config = require_once dirname(dirname(__DIR__)).'/config.php';
        }
        
        if(isset(self::$config[$key])){
            $value = self::$config[$key];
        }
        
        return $value;
    }
}

