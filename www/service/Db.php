<?php
/**
 * 数据库操作
 */
namespace service;

use service\Config;
use service\Exception;

class Db{
    private static $instance; // 实例
    public $pdo = null; // pdo对象
    
    /**
     * 构造 阻止new对象
     */
    private function __construct(){
        $dsn = '';
        $config = array();
        
        $config = Config::get('db');
        if(
            empty($config) || 
            empty($config['type']) ||
            empty($config['host']) ||
            empty($config['port']) ||
            empty($config['database']) ||
            empty($config['charset']) ||
            empty($config['username']) ||
            empty($config['password'])
        ){
            throw new Exception('数据库配置错误');
        }
        
        $dsn = $config['type'].
        ':host='.$config['host'].
        ';port='.$config['port'].
        ';dbname='.$config['database'].
        ';charset='.$config['charset'];
        
        $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
    }
    
    /**
     * 阻止克隆对象
     */
    private function __clone(){}
    
    /**
     * 得到db实例
     */
    public static function getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 获取一个字段
     * @param PDOStatement $pdoStatement 结果集对象
     * @return string 字段内容
     */
    function getOne($pdoStatement){
        $field = '';

        if(!$pdoStatement){
            return $field;
        }

        $field = $pdoStatement->fetchColumn();
        return $field;
    }

    /**
     * 获取一行记录
     * @param PDOStatement $pdoStatement 结果集对象
     * @return array 字段内容
     */
    function getRow($pdoStatement){
        $row = array();

        if(!$pdoStatement){
            return $row;
        }

        $row = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if(empty($row)){
            return array();
        }

        return $row;
    }


    /**
     * 获取全部记录
     * @param PDOStatement $pdoStatement 结果集对象
     * @return array 字段内容
     */
    function getAll($pdoStatement){
        $rows = array();

        if(!$pdoStatement){
            return $rows;
        }

        $rows = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($rows)){
            return array();
        }

        return $rows;
    }
    
    /**
     * 得到pdo错误描述
     * @param PDO $pdo pdo对象
     * @return string 错误描述
     */
    function getPdoError($pdo){
        $errors = array();
        $error = '';

        $errors = $pdo->errorInfo();
        if(!empty($errors[0])){
            $error .= 'SQLSTATE['.$errors[0].']';
        }
        if(!empty($errors[1])){
            $error .= '，驱动错误码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，驱动错误信息：'.$errors[2];
        }

        return $error;
    }
    
    /**
     * 得到预处理结果对象错误描述
     * @param PDOStatement $pdoStatement 结果集对象
     * @return string 错误描述
     */
    function getStatementError($pdoStatement){
        $errors = array();
        $error = '';

        if(!$pdoStatement){
            $error = 'pdostatement对象为false';
            return $error;
        }

        $errors = $pdoStatement->errorInfo();
        if(!empty($errors[0])){
            $error .= 'SQLSTATE['.$errors[0].']';
        }
        if(!empty($errors[1])){
            $error .= '，驱动错误码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，驱动错误信息：'.$errors[2];
        }

        return $error;
    }
}