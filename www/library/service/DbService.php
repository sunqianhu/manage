<?php
/**
 * 数据库助手类
 */
namespace library\service;

use library\service\ConfigService;

class DbService{
    static public $pdo = null;
    
    /**
     * 得到db单例实例
     */
    static function getDbInstance(){
        $dsn = '';
        $config = array();
        
        if(self::$pdo != null){
            return self::$pdo;
        }
    
        $config = ConfigService::getAll();
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
     * 插入
     * @access public
     * @param string $tableName 表
     * @param array $data 记录
     * @return id 新插入记录id
     * @throws Exception
     */
    static function insert($tableName, $data){
        $sql = ''; // sql语句
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $field = ''; // 字段
        $value = ''; // 值
        $sqlFields = array(); // sql字段集合
        $sqlMark = ''; // 一个sql字段标识
        $sqlMarks = array(); // 标识字段标识
        $message = ''; // 错误描述
        
        if(empty($data)){
            return 0;
        }
        
        $pdo = self::getDbInstance();
        foreach($data as $field => $value){
            $sqlFields[$field] = '`'.$field.'`';
            $sqlMarks[$field] = ':'.$field;
        }
        
        $sql = "insert into `".$tableName."`(".implode(',', $sqlFields).") values(".implode(',', $sqlMarks).")";
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $message = self::getPdoError($pdo);
            throw new \Exception($message);
        }
        
        foreach($sqlMarks as $filed => $fieldMark){
            if(is_array($data[$filed]) && count($data[$filed]) > 1){
                $pdoStatement->bindValue($fieldMark, $data[$filed][0], $data[$filed][1]);
            }else{
                $pdoStatement->bindValue($fieldMark, $data[$filed]);
            }
        }
        if(!$pdoStatement->execute()){
            $message = self::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return $pdo->lastInsertId();
    }
    
    /**
     * 删除
     * @access public
     * @param string $tableName 表
     * @param array $where 条件 mark value
     * @return boolean
     * @throws Exception
     */
    static function delete($tableName, $where = array()){
        $sql = ''; // sql语句
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        
        if(!isset($tableName) || $tableName == ''){
            return false;
        }
        $pdo = self::getDbInstance();
        
        $sql = "delete from `".$tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $message = self::getPdoError($pdo);
            throw new \Exception($message);
        }
        
        if(!empty($where['value'])){
            foreach($where['value'] as $whereValueMark => $whereValueValue){
                if(is_array($whereValueValue) && count($whereValueValue) > 1){
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue[0], $whereValueValue[1]);
                }else{
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue);
                }
            }
        }
        if(!$pdoStatement->execute()){
            $message = self::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return true;
    }
    
    /**
     * 更新
     * @access public
     * @param string $tableName 表
     * @param string $data 更新的数据
     * @param array $where 条件 mark value
     * @return boolean
     * @throws Exception
     */
    static function update($tableName, $data, $where = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $dataField = ''; // 字段
        $dataValue = ''; // 值
        $sqlFields = array(); // sql的字段
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        
        if(!isset($tableName) || $tableName == ''){
            return false;
        }
        if(empty($data)){
            return false;
        }
        $pdo = self::getDbInstance();
        
        $sql = "update `".$tableName."` set ";
        foreach($data as $dataField => $dataValue){
            $sqlFields[] = "`".$dataField."` = :".$dataField."";
        }
        $sql .= implode(', ', $sqlFields);
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $message = self::getPdoError($pdo);
            throw new \Exception($message);
        }
        
        foreach($data as $dataField => $dataValue){
            if(is_array($dataValue) && count($dataValue) > 1){
                $pdoStatement->bindValue(':'.$dataField, $dataValue[0], $dataValue[1]);
            }else{
                $pdoStatement->bindValue(':'.$dataField, $dataValue);
            }
        }
        if(!empty($where['value'])){
            foreach($where['value'] as $whereValueMark => $whereValueValue){
                if(is_array($whereValueValue) && count($whereValueValue) > 1){
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue[0], $whereValueValue[1]);
                }else{
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue);
                }
            }
        }
        if(!$pdoStatement->execute()){
            $message = self::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return true;
    }
    
    /**
     * 得到查询条件的全部数据
     * @access public
     * @param string $tableName 表
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @param string $order 排序
     * @param string $limit 限制
     * @return array
     * @throws Exception
     */
    static function selectAll($tableName, $field, $where = array(), $order = '', $limit = ''){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        $datas = array(); // 查询到的数据
        
        if(!isset($tableName) || $tableName == ''){
            return $datas;
        }
        
        $pdo = self::getDbInstance();
        
        $sql = "select $field from `".$tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        if($order != ''){
            $sql .= ' order by '.$order;
        }
        if($limit != ''){
            $sql .= ' limit '.$limit;
        }
        
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $message = self::getPdoError($pdo);
            throw new \Exception($message);
        }
        
        if(!empty($where['value'])){
            foreach($where['value'] as $whereValueMark => $whereValueValue){
                if(is_array($whereValueValue) && count($whereValueValue) > 1){
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue[0], $whereValueValue[1]);
                }else{
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue);
                }
            }
        }
        if(!$pdoStatement->execute()){
            $message = self::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $datas = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($datas)){
            return array();
        }
        
        return $datas;
    }
    
    /**
     * 得到一条记录
     * @access public
     * @param string $tableName 表
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @return array
     */
    static function selectRow($tableName, $field, $where = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        $data = array(); // 查询到的数据
        
        if(!isset($tableName) || $tableName == ''){
            return $data;
        }
        $pdo = self::getDbInstance();
        
        $sql = "select $field from `".$tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        $sql .= ' limit 0,1';
        
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $message = self::getPdoError($pdo);
            throw new \Exception($message);
        }
        
        if(!empty($where['value'])){
            foreach($where['value'] as $whereValueMark => $whereValueValue){
                if(is_array($whereValueValue) && count($whereValueValue) > 1){
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue[0], $whereValueValue[1]);
                }else{
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue);
                }
            }
        }
        if(!$pdoStatement->execute()){
            $message = self::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $data = $pdoStatement->fetch(\PDO::FETCH_ASSOC);
        if(empty($data)){
            return array();
        }
        
        return $data;
    }
    
    /**
     * 得到一个字段内容
     * @access public
     * @param string $tableName 表
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @return string
     * @throws Exception
     */
    static function selectOne($tableName, $field, $where = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        $content = ''; // 查询到的数据
        
        if(!isset($tableName) || $tableName == ''){
            return $content;
        }
        $pdo = self::getDbInstance();
        
        $sql = "select ".$field." from `".$tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        $sql .= ' limit 0,1';
        $pdoStatement = $pdo->prepare($sql);
        if($pdoStatement === false){
            $message = self::getPdoError($pdo);
            throw new \Exception($message);
        }
        
        if(!empty($where['value'])){
            foreach($where['value'] as $whereValueMark => $whereValueValue){
                if(is_array($whereValueValue) && count($whereValueValue) > 1){
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue[0], $whereValueValue[1]);
                }else{
                    $pdoStatement->bindValue($whereValueMark, $whereValueValue);
                }
            }
        }
        if(!$pdoStatement->execute()){
            $message = self::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $content = $pdoStatement->fetchColumn();
        
        return $content;
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
            $error .= '，驱动错误码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，驱动错误信息：'.$errors[2];
        }

        return $error;
    }
}