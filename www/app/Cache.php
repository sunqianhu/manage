<?php
/**
 * 缓存
 */
namespace app;

class Cache{
    
    static $config = array(); // 配置
    
    /**
     * 设置缓存
     * @param String $key 缓存key
     * @param String $value 缓存value
     * @access public
     */
    static function set($key, $value){
        $path = '';
        
        // 配置
        if(empty(self::$config)){
            self::$config = Config::get('cache');
        }
        if(!self::$config['open']){
            return false;
        }
        if(empty(self::$config['dir'])){
            return false;
        }
        
        // 保存
        $path = self::$config['dir'].$key.'.txt';
        file_put_contents($path, $value);
        
        return true;
    }
    
    /**
     * 获取缓存
     * @param String $key 缓存key
     * @param String $default 默认返回
     * @access public
     */
    static function get($key, $default = ''){
        $path = '';
        $timeLast = 0; // 文件最后修改时间
        $timeNow = time(); // 当前时间
        
        // 配置
        if(empty(self::$config)){
            self::$config = Config::get('cache');
        }
        if(!self::$config['open']){
            return $default;
        }
        if(empty(self::$config['dir'])){
            return $default;
        }
        
        // 存在
        $path = self::$config['dir'].$key.'.txt';
        if(!file_exists($path)){
            return $default;
        }
        
        // 缓存过期
        $timeLast = filemtime($path);
        if($timeNow - $timeLast > self::$config['time']){
            return $default;
        }
        
        return file_get_contents($path);
    }
}
