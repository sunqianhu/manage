<?php
/**
 * 数据库操作辅助类
 */
namespace library;

use library\Config;

class DbHelper{
    static public $pdo;
    public $error = ''; // 错误
    
    /**
     * 得到错误
     * @return string 错误描述
     */
    function getError(){
        return $this->error;
    }
    
    /**
     * 设置错误
     * @param string $error 错误描述
     * @return boolean
     */
    function setError($error){
        return $this->error = $error;
    }
    
    /**
     * 得到pdo单例实例
     * @return object pdo对象
     */
    function getPdo(){
        $pdo = null;
        $dsn = '';
        $config = array();
        
        if(self::$pdo != null){
            return self::$pdo;
        }
    
        $config = Config::getAll();
        if(
            empty($config) || 
            empty($config['db_type']) ||
            empty($config['db_host']) ||
            empty($config['db_port']) ||
            empty($config['db_database']) ||
            empty($config['db_charset']) ||
            empty($config['db_username']) ||
            empty($config['db_password'])
        ){
            $this->setError('数据库配置参数错误');
            return $pdo;
        }
        
        $dsn = $config['db_type'].
        ':host='.$config['db_host'].
        ';port='.$config['db_port'].
        ';dbname='.$config['db_database'].
        ';charset='.$config['db_charset'];
        $pdo = new \PDO($dsn, $config['db_username'], $config['db_password']);
        
        self::$pdo = $pdo;
        return $pdo;
    }
    
    /**
     * 执行sql语句
     * @access public
     * @param PDO $pdo pdo对象
     * @param string $sql sql
     * @param array $data 数据
     * @return PDOStatement PDOStatement对象
     */
    function query($pdo, $sql, $data = array()){
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        
        if(empty($sql)){
            $this->setError('sql不能为空');
            return $pdoStatement;
        }
        
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = $this->getPdoError($pdo);
            $this->setError($error);
            return null;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = $this->getPodStatementError($pdoStatement);
            $this->setError($error);
            return null;
        }
        
        return $pdoStatement;
    }
    
    /**
     * 得到查询条件的全部数据
     * @access public
     * @param PDO $pdoStatement 结果集对象
     * @param integer $type 返回内容格式
     * @return array
     */
    function fetchAll($pdoStatement, $type = \PDO::FETCH_ASSOC){
        $datas = array();
        
        $datas = $pdoStatement->fetchAll($type);
        if(empty($datas)){
            return array();
        }
        
        return $datas;
    }
    
    /**
     * 从结果集中获取下一行
     * @access public
     * @param PDO $pdoStatement 结果集对象
     * @param integer $type 返回内容格式
     * @return array
     */
    function fetch($pdoStatement, $type = \PDO::FETCH_ASSOC){
        $data = array();
        
        $data = $pdoStatement->fetch($type);
        if(empty($data)){
            return array();
        }
        
        return $data;
    }
    
    /**
     * 从结果集中的下一行返回单独的一列
     * @access public
     * @param PDO $pdoStatement 结果集对象
     * @param array $data 数据
     * @return string
     */
    function fetchColumn($pdoStatement, $columnNumber = 0){
        $field = '';
        
        $field = $pdoStatement->fetchColumn($columnNumber);
        if($field === false){
            return '';
        }
        
        return $field;
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
            $error .= '，驱动错误代码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，驱动错误描述：'.$errors[2];
        }

        return $error;
    }
    
    /**
     * 得到预处理结果对象错误描述
     * @param PDOStatement $pdoStatement 结果集对象
     * @return string 错误描述
     */
    function getPodStatementError($pdoStatement){
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
            $error .= '，驱动错误代码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，驱动错误描述：'.$errors[2];
        }

        return $error;
    }
}