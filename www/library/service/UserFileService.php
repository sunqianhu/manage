<?php
/**
 * 用户文件服务
 */
namespace library\service;

class UserFileService{
    /**
     * 得到文件访问url
     * @param string $path 文件全路径
     * @return string 文件访问url
     */
    static function getUrl($path){
        $url = '';
        $configFileDomain = ConfigService::getOne('user_file_domain');
        
        if(empty($path)){
            return $url;
        }
        
        $url = $configFileDomain.$path;
        
        return $url;
    }
}
