<?php
/**
 * 基类模型
 */
namespace app\model;

use \app\Config;

class BaseModel{
    static public $pdoOnly = null; // 单例
    public $pdo = null;
    
    /**
     * 构造函数
     */
    function __construct(){
        $this->getPdoInstance();
    }
    
    /**
     * 构造函数
     */
    function getPdoInstance(){
        $dsn = '';    
        $config = array();
        
        if(self::$pdoOnly != null){
            $this->pdo = self::$pdoOnly;
            return;
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
            throw new \Exception('数据库配置错误');
        }
        
        $dsn = $config['type'].
        ':host='.$config['host'].
        ';port='.$config['port'].
        ';dbname='.$config['database'].
        ';charset='.$config['charset'];
        self::$pdoOnly = new \PDO($dsn, $config['username'], $config['password']);
        $this->pdo = self::$pdoOnly;
    }
    
    /**
     * 插入
     * @access public
     * @param array $data 记录
     * @return id 新插入记录id
     * @throws Exception
     */
    function insert($data){
        $sql = ''; // sql语句
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
        if(!isset($this->tableName) || $this->tableName == ''){
            return 0;
        }
        
        foreach($data as $field => $value){
            $sqlFields[$field] = '`'.$field.'`';
            $sqlMarks[$field] = ':'.$field;
        }
        
        $sql = "insert into `".$this->tableName."`(".implode(',', $sqlFields).") values(".implode(',', $sqlMarks).")";
        $pdoStatement = $this->pdo->prepare($sql);
        foreach($sqlMarks as $filed => $fieldMark){
            if(is_array($data[$filed]) && count($data[$filed]) > 1){
                $pdoStatement->bindValue($fieldMark, $data[$filed][0], $data[$filed][1]);
            }else{
                $pdoStatement->bindValue($fieldMark, $data[$filed]);
            }
        }
        if(!$pdoStatement->execute()){
            $message = $this->getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * 删除
     * @access public
     * @param array $where 条件 mark value
     * @return boolean
     * @throws Exception
     */
    function delete($where = array()){
        $sql = ''; // sql语句
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return false;
        }
        $sql = "delete from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        
        $pdoStatement = $this->pdo->prepare($sql);
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
            $message = $this->getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return true;
    }
    
    /**
     * 更新
     * @access public
     * @param string $datas 更新的数据
     * @param array $where 条件 mark value
     * @return boolean
     * @throws Exception
     */
    function update($datas, $where = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $dataField = ''; // 字段
        $dataValue = ''; // 值
        $sqlFields = array(); // sql的字段
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return false;
        }
        if(empty($datas)){
            return false;
        }
        $sql = "update `".$this->tableName."` set ";
        foreach($datas as $dataField => $dataValue){
            $sqlFields[] = "`".$dataField."` = :".$dataField."";
        }
        $sql .= implode(', ', $sqlFields);
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        
        $pdoStatement = $this->pdo->prepare($sql);
        foreach($datas as $dataField => $dataValue){
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
            $message = $this->getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return true;
    }
    
    /**
     * 得到查询条件的全部数据
     * @access public
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @param string $order 排序
     * @param string $limit 限制
     * @return array
     * @throws Exception
     */
    function select($field, $where = array(), $order = '', $limit = ''){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        $datas = array(); // 查询到的数据
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return $datas;
        }
        
        $sql = "select $field from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        if($order != ''){
            $sql .= ' '.$order;
        }
        if($limit != ''){
            $sql .= ' '.$limit;
        }
        
        $pdoStatement = $this->pdo->prepare($sql);
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
            $message = $this->getStatementError($pdoStatement);
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
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @return array
     */
    function selectRow($field, $where = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        $data = array(); // 查询到的数据
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return $data;
        }
        $sql = "select $field from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        $sql .= ' limit 0,1';
        
        $pdoStatement = $this->pdo->prepare($sql);
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
            $message = $this->getStatementError($pdoStatement);
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
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @return string
     * @throws Exception
     */
    function selectOne($field, $where = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        $content = ''; // 查询到的数据
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return $content;
        }
        $sql = "select ".$field." from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        $sql .= ' limit 0,1';
        $pdoStatement = $this->pdo->prepare($sql);
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
            $message = $this->getStatementError($pdoStatement);
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

        $errors = $this->pdo->errorInfo();
        if(!empty($errors[0])){
            $error .= 'SQLSTATE['.$errors[0].']';
        }
        if(!empty($errors[1])){
            $error .= '，错误码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，错误信息：'.$errors[2];
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
            $error .= '，错误码：'.$errors[1];
        }
        if(!empty($errors[2])){
            $error .= '，错误信息：'.$errors[2];
        }

        return $error;
    }
}