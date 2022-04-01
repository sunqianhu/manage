<?php
/**
 * 基类模型
 */
namespace app\model;

use app\Config;
use app\Exception;

class Base{
    public $pdo = null; // pdo操作对象
    
    /**
     * 得到pdo对象
     */
    function getPdo(){
        $dsn = '';
        $config = array();
        
        if($this->pdo){
            return $this->pdo;
        }
        
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
        
        return $this->pdo;
    }
    
    /**
     * 增加
     * @access public
     * @param string $sql sql命令
     * @param array $parameters sql参数
     * @return id
     */
    function add($sql, $parameters = array()){
        $pdo = null;
        $pdoStatement = null;
        $errorMessage = '';
        $id = 0;
        
        $pdo = $this->getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $key => $parameter){
            $pdoStatement->bindValue($key, $parameter);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = $this->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        $id = $pdo->lastInsertId();
        return $id;
    }
    
    /**
     * 删除
     * @access public
     * @param string $sql sql命令
     * @param array $parameters sql参数
     * @return boolean
     */
    function delete($sql, $parameters = array()){
        $pdo = null;
        $pdoStatement = null;
        $errorMessage = '';
        
        $pdo = $this->getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $key => $parameter){
            $pdoStatement->bindValue($key, $parameter);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = $this->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        return true;
    }
    
    /**
     * 修改
     * @access public
     * @param string $sql sql命令
     * @param array $parameters sql参数
     * @return boolean
     */
    function edit($sql, $parameters = array()){
        $pdo = null;
        $pdoStatement = null;
        $errorMessage = '';
        
        $pdo = $this->getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $key => $parameter){
            $pdoStatement->bindValue($key, $parameter);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = $this->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        return true;
    }
    
    /**
     * 查询一个字段
     * @access public
     * @param string $sql sql命令
     * @param array $parameters sql参数
     * @return string 字段内容
     */
    function getOne($sql, $parameters = array()){
        $pdo = null;
        $pdoStatement = null;
        $errorMessage = '';
        $field = '';
        
        $pdo = $this->getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $key => $parameter){
            $pdoStatement->bindValue($key, $parameter);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = $this->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        $field = $pdoStatement->fetchColumn();

        return $field;
    }
    
    /**
     * 查询一行
     * @access public
     * @param string $sql sql命令
     * @param array $parameters sql参数
     * @return string 字段内容
     */
    function getRow($sql, $parameters = array()){
        $pdo = null;
        $pdoStatement = null;
        $errorMessage = '';
        $row = array();
        
        $pdo = $this->getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $key => $parameter){
            $pdoStatement->bindValue($key, $parameter);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = $this->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
        }
        
        $row = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if(empty($row)){
            return array();
        }

        return $row;
    }
    
    /**
     * 查询全部
     * @access public
     * @param string $sql sql命令
     * @param array $parameters sql参数
     * @return string 字段内容
     */
    function getAll($sql, $parameters = array()){
        $pdo = null;
        $pdoStatement = null;
        $errorMessage = '';
        $rows = array();
        
        $pdo = $this->getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $key => $parameter){
            $pdoStatement->bindValue($key, $parameter);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = $this->getStatementError($pdoStatement);
            throw new Exception($errorMessage);
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