<?php
/**
 * 用户
 */
namespace app\model;

use app\helper\Db;
use app\Exception;

class Admin extends Base{
    /**
     * 获取一个字段
     * @access public
     * @param string $username 用户名
     * @param string $password 密码
     * @return array 用户记录
     */
    function getRowByUsernamePassword($username, $password){
        $sql = '';
        $db = DB::getInstance();
        $pdoStatement = null;
        $admin = array();
        $errorMessage = '';
        
        $sql = "select * from admin where username = :username and `password` = :password limit 0,1";
        $pdoStatement = $db->pdo->prepare($sql);
        $pdoStatement->bindValue(':username', $_POST['username']);
        $pdoStatement->bindValue(':password', md5($_POST['password']));
        if(!$pdoStatement->execute()){
            $errorMessage = $db->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        $admin = $db->getRow($pdoStatement);
        if(empty($admin)){
            throw new Exception('用户名或密码错误');
        }
        
        return $admin;
    }
}