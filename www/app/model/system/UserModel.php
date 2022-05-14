<?php
/**
 * 用户
 */
namespace app\model\system;

use app\service\DbService;
use app\model\BaseModel;

class UserModel extends BaseModel{
    /**
     * 获取一个字段
     * @access public
     * @param string $username 用户名
     * @param string $password 密码
     * @return array 用户记录
     */
    function getRowByUsernamePassword($username, $password){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $user = array();
        $errorMessage = '';
        
        $pdo = DbService::getInstance();
        
        $sql = "select * from user where username = :username and `password` = :password limit 0,1";
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':username', $_POST['username']);
        $pdoStatement->bindValue(':password', md5($_POST['password']));
        if(!$pdoStatement->execute()){
            $errorMessage = DbService::getStatementError($pdoStatement);
            throw new \Exception($errorMessage);
        }
        
        $user = DbService::getRow($pdoStatement);
        if(empty($user)){
            throw new \Exception('用户名或密码错误');
        }
        
        return $user;
    }
}