<?php
/**
 * 用户
 */
namespace model;

use service\Db;
use service\Exception;

class User extends Base{
    /**
     * 获取一个字段
     * @access public
     * @param string $userid 用户名
     * @param string $password 密码
     * @return array 用户记录
     */
    function getRowByUseridPassword($userid, $password){
        $sql = '';
        $db = DB::getInstance();
        $pdoStatement = null;
        $user = array();
        $errorMessage = '';
        
        $sql = "select * from user where userid = :userid and `password` = :password limit 0,1";
        $pdoStatement = $db->pdo->prepare($sql);
        $pdoStatement->bindValue(':userid', $_POST['userid']);
        $pdoStatement->bindValue(':password', md5($_POST['password']));
        if(!$pdoStatement->execute()){
            $errorMessage = $db->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        $user = $db->getRow($pdoStatement);
        if(empty($user)){
            throw new Exception('用户名或密码错误');
        }
        
        return $user;
    }
}