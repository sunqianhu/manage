<?php
/**
 * 数据库助手类
 */
namespace library;

use \library\Config;

class Db{
    static public $pdo = null;
    static public $error = ''; // 错误
    
    /**
     * 得到错误
     */
    static function getError(){
        return self::$error;
    }
    
    /**
     * 设置错误
     * @param string $error 错误描述
     * @return boolean
     */
    static function setError($error){
        return self::$error = $error;
    }
    
    /**
     * 得到db单例实例
     */
    static function getPdoInstance(){
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
            throw new \Exception('数据库配置错误');
        }
        
        $dsn = $config['db_type'].
        ':host='.$config['db_host'].
        ';port='.$config['db_port'].
        ';dbname='.$config['db_database'].
        ';charset='.$config['db_charset'];
        self::$pdo = new \PDO($dsn, $config['db_username'], $config['db_password']);
        
        return self::$pdo;
    }
    
    /**
     * 执行
     * @access public
     * @param string $sql sql模式
     * @param array $data 数据
     * @return boolean
     */
    static function execute($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return false;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return false;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return false;
        }
        
        return true;
    }
    
    /**
     * 插入
     * @access public
     * @param string $sql sql
     * @param array $data 数据
     * @return id 新插入记录id
     */
    static function insert($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return false;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return false;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return false;
        }
        
        return $pdo->lastInsertId();
    }
    
    /**
     * 删除
     * @access public
     * @param string $sql sql模式
     * @param array $data 数据
     * @return boolean
     */
    static function delete($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return false;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return false;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return false;
        }
        
        return true;
    }
    
    /**
     * 更新
     * @access public
     * @param string $sql sql模式
     * @param array $data 数据
     * @return boolean
     */
    static function update($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return false;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return false;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return false;
        }
        
        return true;
    }
    
    /**
     * 得到查询条件的全部数据
     * @access public
     * @param string $sql sql模式
     * @param array $data 数据
     * @return array
     */
    static function selectAll($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        $results = array();
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return $results;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return $results;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return $results;
        }
        
        $results = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($results)){
            return array();
        }
        
        return $results;
    }
    
    /**
     * 得到一条记录
     * @access public
     * @param string $sql sql模式
     * @param array $data 数据
     * @return array
     */
    static function selectRow($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        $result = array();
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return $result;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return $result;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return $result;
        }
        
        $result = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if(empty($result)){
            return array();
        }
        
        return $result;
    }
    
    /**
     * 得到一个字段内容
     * @access public
     * @param string $sql sql模式
     * @param array $data 数据
     * @return array
     */
    static function selectOne($sql, $data = array()){
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $error = ''; // 错误描述
        $result = '';
        
        if(empty($sql)){
            self::setError('sql不能为空');
            return $result;
        }
        
        $pdo = self::getPdoInstance();
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $error = self::getPdoError($pdo);
            self::setError($error);
            return $result;
        }
        foreach($data as $field => $value){
            if(is_array($value) && count($value) > 1){
                $pdoStatement->bindValue($field, $value[0], $value[1]);
            }else{
                $pdoStatement->bindValue($field, $value);
            }
        }
        if(!$pdoStatement->execute()){
            $error = self::getStatementError($pdoStatement);
            self::setError($error);
            return $result;
        }
        
        $result = $pdoStatement->fetchColumn();
        return $result;
    }
    
    /**
     * 得到pdo错误描述
     * @param PDO $pdo pdo对象
     * @return string 错误描述
     */
    static function getPdoError($pdo){
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
    static function getStatementError($pdoStatement){
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