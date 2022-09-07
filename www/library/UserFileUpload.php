<?php
/**
 * 用户文件上传服务
 */
namespace library;

use library\Db;
use library\Config;
use library\UserFile;
use library\Ip;

class UserFileUpload{

    /**
     * 上传
     * @param string $dirModule 目录模块 
     * @param string $fieldName 文件表单字段名称 
     * @return boolean 上传结果
     */
    static function upload($dirModule, $fieldName){
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
        $userFileModel = new UserFileModel();
        $data = array();
        $return = array();
        
        $configUserFilePath = Config::getOne('user_file_path');
        if(empty($configUserFilePath)){
            throw new \Exception('file_path配置错误');
        }
        
        if(empty($_FILES[$fieldName])){
            throw new \Exception('文件表单字段名称错误');
        }
        $file = $_FILES[$fieldName];
        
        if($file['error']){
            throw new \Exception('文件上传错误，错误号：'.$_FILES['file']['error']);
        }
        
        if(!in_array($file['type'], array_values($typeAllows))){
            throw new \Exception('此mime类型的文件不允许上传');
        }
        $extension = array_search($file['type'], $typeAllows);
        
        if($file['size'] > $sizeMax){
            throw new \Exception('文件超过200Mb');
        }

        if($dirModule !== ''){
            $dir = $dirModule.'/'.$dir;
        }
        if(!file_exists($configUserFilePath.$dir)){
            if(!@mkdir($configUserFilePath.$dir, 0755, true)){
                throw new \Exception('文件夹创建失败');
            }
        }
        
        $name = time().rand(1000, 9999).'.'.$extension;
        $path = $dir.'/'.$name;
        if(!move_uploaded_file($file['tmp_name'], $configUserFilePath.$path)){
            throw new \Exception('文件上传失败');
        }
        
        // 记录
        $data = array(
            'department_id'=>$_SESSION['department']['id'],
            'user_id'=>$_SESSION['user']['id'],
            'module_id'=>$dirModule,
            'name'=>$file['name'],
            'path'=>$path,
            'size'=>$file['size'],
            'type'=>$file['type'],
            'ip'=>Ip::get(),
            'time_add'=>time()
        );
        Db::insert($data);
        
        // 赋值
        $return['path'] = $path;
        $return['url'] = UserFile::getUrl($path);
        $return['name'] = $name;
        $return['extension'] = $extension;
        $return['type'] = $file['type'];
        $return['size'] = $file['size'];
        
        return $return;
    }
}
