<?php
/**
 * 缓存
 */
namespace library\core;

class Cache{
    public $dir = array(); // 缓存目录
    
    /**
     * 设置缓存
     * @access public
     * @param string $key 缓存key
     * @param string $value 缓存value
     * @param int $timeOut 超时时间
     * @return boolean 布尔
     */
    function set($key, $value, $timeOut = 0){
        $path = '';
        
        if($timeOut === 0){
            $timeOut = time() + 600;
        }
        
        // 配置
        if(empty($this->dir)){
            $this->dir = Config::getOne('cache_dir');
        }
        
        // 保存
        $path = $this->dir.$key.'.txt';
        file_put_contents($path, $value);
        touch($path, $timeOut);
        
        return true;
    }
    
    /**
     * 获取缓存
     * @access public
     * @param string $key 缓存key
     * @return string 缓存内容
     */
    function get($key){
        $path = '';
        $fileTimeLast = 0; // 文件最后修改时间
        $return = '';
        
        // 配置
        if(empty($this->dir)){
            $this->dir = Config::getOne('cache_dir');
        }
        
        // 存在
        $path = $this->dir.$key.'.txt';
        if(!file_exists($path)){
            return $return;
        }
        
        // 过期
        $fileTimeLast = filemtime($path);
        if($fileTimeLast - time() < 0){
            return $return;
        }
        
        return file_get_contents($path);
    }
}
