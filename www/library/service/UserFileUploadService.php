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
     * 文件
     * @param string $dirModule 目录模块 
     * @param string $fieldName 文件表单字段名称 
     * @return boolean 上传结果
     */
    static function file($dirModule, $fieldName){
        $file = array(); // 上传file数据
        $configUserFilePath = ''; // 配置文件路径
        $path = ''; // 文件全路径
        $dir = date('Y/m/d/'); // 文件目录
        $name = ''; // 文件名
        $extension = ''; // 文件扩展名
        $typeAllows = array(
            'jpeg' => 'image/jpeg', 
            'jpg' => 'image/jpg', 
            'pjpeg' => 'image/pjpeg', 
            'png' => 'image/png', 
            'gif' => 'image/gif', 
            'rar' => 'application/x-rar-compressed',
            'zip' => 'application/zip',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt' => 'text/plain',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'flv' => 'video/x-flv',
            '3gp' => 'video/3gpp',
            'mp3' => 'audio/mpeg'
        ); // 允许的文件类型
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
        
        if(!in_array($file['type'], array_values($typeAllows))){
            self::$message = '此mime类型的文件不允许上传';
            return false;
        }
        $extension = array_search($file['type'], $typeAllows);
        
        if($file['size'] > $sizeMax){
            self::$message = '文件超过200Mb';
            return false;
        }

        if($dirModule !== ''){
            $dir = $dirModule.'/'.$dir;
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
