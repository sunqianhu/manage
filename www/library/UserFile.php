<?php
/**
 * 用户文件服务
 */
namespace library;

use library\Config;

class UserFile{
    /**
     * 得到文件访问url
     * @param string $path 文件全路径
     * @return string 文件访问url
     */
    static function getUrl($path){
        $url = '';
        $configFileDomain = Config::getOne('user_file_domain');
        
        if(empty($path)){
            return $url;
        }
        
        $url = $configFileDomain.$path;
        
        return $url;
    }
    
    /**
     * copy附件到本地
     * @param string $url 文件网络路径 
     * @param string $path 本地文件相对路径 
     * @return string path
     */
    static function copy($url, $path){
        $configUserFilePath = ''; // 配置文件路径
        $dir = dirname($path);
        
        $configUserFilePath = Config::getOne('user_file_path');
        if(empty($configUserFilePath)){
            throw new \Exception('file_path配置错误');
            return false;
        }
        if(!file_exists($configUserFilePath.$dir)){
            if(!@mkdir($configUserFilePath.$dir, 0755, true)){
                throw new \Exception('文件夹创建失败');
                return false;
            }
        }
        
        if(!copy($url, $configUserFilePath.$path)){
            throw new \Exception('文件copy失败');
            return false;
        }
        
        return true;
    }
}
