<?php
/**
 * 首页
 */
namespace app\controller;

use \app\controller\Base;
use \app\View;
use \app\model\User;

class Index extends Base{
    /**
     * 入口
     */
    function main(){
        $sql = "";
        $user = new User();
        $sql = "insert into user(username, password) values(:username, :password)";
        $sql = "update user set username = :username where id = :id";
        $sql = "delete from user where id = :id";
        $parameters = array(
            ':id'=>10012
        );
        echo $user->delete($sql, $parameters);
        
        
        //$this->display("index.php", '大家好');
    }
}