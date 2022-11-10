<?php
/**
 * 用户文件
 */
namespace library\core;

use library\core\Config;

class UserFile{
    public $error = ''; // 错误
    
    /**
     * 得到错误
     * @return string 错误描述
     */
    function getError(){
        return $this->error;
    }
    
    /**
     * 设置错误
     * @param string $error 错误描述
     * @return boolean
     */
    function setError($error){
        return $this->error = $error;
    }

    /**
     * 得到文件访问url
     * @param string $path 文件全路径
     * @return string 文件访问url
     */
    function getUrl($path){
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
    function copy($url, $path){
        $configUserFilePath = ''; // 配置文件路径
        $dir = dirname($path);
        
        $configUserFilePath = Config::getOne('user_file_path');
        if(empty($configUserFilePath)){
            $this->setError('file_path配置错误');
            return false;
        }
        if(!file_exists($configUserFilePath.$dir)){
            if(!@mkdir($configUserFilePath.$dir, 0755, true)){
                $this->setError('文件夹创建失败');
                return false;
            }
        }
        
        if(!copy($url, $configUserFilePath.$path)){
            $this->setError('文件复制失败');
            return false;
        }
        
        return true;
    }
}
