<?php
/**
 * 用户文件上传服务
 */
namespace library\service;

use library\service\ConfigService;
use library\service\UserFileService;

class UserFileUploadService{
    static $path = ''; // 文件全路径
    static $name = ''; // 文件名
    static $extension = ''; // 扩展名
    static $size = ''; // 文件大小
    static $url = ''; // 文件访问域名
    static $message = ''; // 上传描述
    
    /**
     * 上传
     * @param string $fieldName 文件表单字段名称 
     * @return boolean 上传结果
     */
    static function upload($fieldName){
        $file = array(); // 上传file数据
        $configUserFilePath = ''; // 配置文件路径
        $path = ''; // 文件全路径
        $dir = date('Y/m/d/'); // 文件目录
        $name = ''; // 文件名
        $extension = ''; // 文件扩展名
        $extensionTemps = array(); // 扩展名的临时数组
        $extensionAllows = array('gif', 'png', 'jpg', 'jpeg', 'txt', 'zip', 'tar', 'gz', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'vsdx', 'pdf', 'mp4', 'rmvb', 'avi', 'flv', 'mp3');
        $sizeMax = 1024 * 1024 * 200; // 文件上传大小限制
        
        $configUserFilePath = ConfigService::getOne('user_file_path');
        if(empty($configUserFilePath)){
            self::$message = 'file_path配置错误';
            return false;
        }
        
        if(empty($_FILES[$fieldName])){
            self::$message = '文件表单字段名称错误';
            return false;
        }
        $file = $_FILES[$fieldName];
        
        if($file['error']){
            self::$message = '文件上传错误，错误号：'.$_FILES['file']['error'];
            return false;
        }
        
        $extensionTemps = explode('.', $file['name']);
        $extension = strtolower(trim(array_pop($extensionTemps)));
        if($extension == ''){
            self::$message = '文件扩展名错误';
            return false;
        }
        if(!in_array($extension, $extensionAllows)){
            self::$message = '文件类型不允许上传';
            return false;
        }
        
        if($file['size'] > $sizeMax){
            self::$message = '文件超过200Mb';
            return false;
        }

        if(!file_exists($configUserFilePath.$dir)){
            if(!@mkdir($configUserFilePath.$dir, 0755, true)){
                self::$message = '文件夹创建失败';
                return false;
            }
        }
        
        $name = time().rand(1000, 9999).'.'.$extension;
        $path = $dir.'/'.$name;
        if(!move_uploaded_file($file['tmp_name'], $configUserFilePath.$path)){
            self::$message = '文件上传失败';
            return false;
        }
        
        self::$path = $path;
        self::$name = $name;
        self::$extension = $extension;
        self::$size = $file['size'];
        self::$url = UserFileService::getUrl($path);
        self::$message = '上传成功';
        
        return true;
    }
}
