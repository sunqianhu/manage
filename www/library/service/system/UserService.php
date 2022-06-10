<?php
/**
 * 用户服务
 */
namespace library\service\system;

use \library\model\system\UserModel;

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
    
}
