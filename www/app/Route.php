<?php
/**
 * 路由
 */
namespace app;

use \app\Exception;

class Route{
    /**
     * 得到控制器对象
     * @access public
     * @return 控制器对象
     */
    static function getController(){
        $c = '';
        $match = array();
        
        if(empty($_GET['c'])){
            $_GET['c'] = 'Index';
        }
        
        $c = $_GET['c'];
        if(strlen($c) > 32){
            throw new Exception("c参数不能大于32位");
        }
        if(!preg_match('/^[_0-9a-zA-Z]+$/', $c)){
            throw new Exception("c参数只能由字母数字或下划线组成");
        }
        
        $c = ucfirst($c);
        $c = '\\app\\controller\\'.$_GET['c'];
        
        return new $c;
    }
    
    /**
     * 得到方法
     * @access public
     * @return 控制器对象
     */
    static function getAction(){
        $a = '';
        
        if(empty($_GET['a'])){
            $_GET['a'] = 'main';
        }
        
        $a = $_GET['a'];
        if(strlen($a) > 32){
            throw new Exception("a参数不能大于32位");
        }
        if(!preg_match('/^[_0-9a-zA-Z]+$/', $a)){
            throw new Exception("a参数只能由字母数字或下划线组成");
        }
        
        return $_GET['a'];
    }
}

