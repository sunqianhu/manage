<?php
/**
 * 文件上传服务
 */
namespace library;

use \library\Config;
use \library\UserFile;

class UserFileUpload{
    static public $name = ''; // 文件名
    static public $path = ''; // 文件路径
    static public $url = ''; // 文件url
    static public $extension = ''; // 文件扩展名
    static public $type = ''; // 类型
    static public $size = ''; // 文件大小
    static public $error = ''; // 错误描述
    
    /**
     * 上传
     * @param string $dirModule 目录模块 
     * @param string $fieldName 文件表单字段名称 
     * @return boolean 上传结果 true | false
     */
    static function upload($dirModule, $fieldName){
        $configUserFilePath = ''; // 配置文件路径
        $file = array(); // 上传file数据
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
        $sizeMax = 1024 * 1024 * 500; // 文件上传大小限制
        $data = array();
        $return = array();
        
        $configUserFilePath = Config::getOne('user_file_path');
        if(empty($configUserFilePath)){
            self::setError('file_path配置错误');
            return false;
        }
        if(empty($_FILES[$fieldName])){
            self::setError('文件表单字段名称错误');
            return false;
        }
        $file = $_FILES[$fieldName];
        if($file['error']){
            self::setError('文件上传错误，错误号：'.$_FILES['file']['error']);
            return false;
        }
        if(!in_array($file['type'], array_values($typeAllows))){
            self::setError('此mime类型的文件不允许上传');
            return false;
        }
        $extension = array_search($file['type'], $typeAllows);
        if($file['size'] > $sizeMax){
            self::setError('文件超过500Mb');
            return false;
        }

        if($dirModule !== ''){
            $dir = $dirModule.'/'.$dir;
        }
        if(!file_exists($configUserFilePath.$dir)){
            if(!@mkdir($configUserFilePath.$dir, 0755, true)){
                self::setError('文件夹创建失败');
                return false;
            }
        }
        
        $name = md5(time().rand(1000, 9999)).'.'.$extension;
        $path = $dir.'/'.$name;
        if(!move_uploaded_file($file['tmp_name'], $configUserFilePath.$path)){
            self::setError('move_uploaded_file文件上传失败');
            return false;
        }
        
        // 赋值
        self::$path = $path;
        self::$url = UserFile::getUrl($path);
        self::$name = $file['name'];
        self::$extension = $extension;
        self::$type = $file['type'];
        self::$size = $file['size'];
        
        return true;
    }
    
    /**
     * 得到错误
     */
    static function getError(){
        return self::$error;
    }
    
    /**
     * 设置错误
     * @param string $error 错误描述
     * @return boolean
     */
    static function setError($error){
        return self::$error = $error;
    }
}
