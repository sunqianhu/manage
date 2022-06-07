<?php
/**
 * 缓存
 */
namespace library\service;

class CacheService{
    
    static $dir = array(); // 缓存目录
    
    /**
     * 设置缓存
     * @access public
     * @param String $key 缓存key
     * @param String $value 缓存value
     * @return boolean 布尔
     */
    static function set($key, $value){
        $path = '';
        
        // 配置
        if(empty(self::$dir)){
            self::$dir = ConfigService::getOne('cache_dir');
        }
        
        // 保存
        $path = self::$dir.$key.'.txt';
        file_put_contents($path, $value);
        
        return true;
    }
    
    /**
     * 获取缓存
     * @access public
     * @param String $key 缓存key
     * @param String $return 默认返回
     * @return string 缓存内容
     */
    static function get($key, $return = ''){
        $path = '';
        $fileTimeLast = 0; // 文件最后修改时间
        
        // 配置
        if(empty(self::$dir)){
            self::$dir = ConfigService::getOne('cache_dir');
        }
        
        // 存在
        $path = self::$dir.$key.'.txt';
        if(!file_exists($path)){
            return $return;
        }
        
        // 缓存过期
        $fileTimeLast = filemtime($path);
        if(time() - $fileTimeLast > 600){
            return $return;
        }
        
        return file_get_contents($path);
    }
    
    
}
