<?php
/**
 * 用户服务
 */
namespace library\service\system;

use library\model\system\UserModel;
use library\service\ConfigService;

class userService{
    
    /**
     * 得到用户姓名
     * @access public
     * @param int $id 用户id
     * @return string 用户姓名
     */
    static function getName($id){
        $userModel = new UserModel();
        $name = '';
        
        $name = $userModel->selectOne('name', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$id
            )
        ));
        
        return $name;
    }
    
    /**
     * 得到用户头像url
     * @access public
     * @param string $path 头像路径
     * @return string 头像url
     */
    static function getHeadUrl($path){
        $config = ConfigService::getAll();
        $url = '';
        
        if($path === ''){
            $url = $config['app_domain'].'image/user_head.png';
        }else{
            $url = $config['user_file_domain'].$path;
        }
        
        return $url;
    }
}
