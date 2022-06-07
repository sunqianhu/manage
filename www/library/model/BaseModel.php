<?php
/**
 * 基类模型
 */
namespace library\model;

use library\service\DbService;

class BaseModel{

    /**
     * 插入
     * @access public
     * @param array $data 记录
     * @return id 新插入记录id
     * @throws Exception
     */
    function insert($data){
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
        if(!isset($this->tableName) || $this->tableName == ''){
            return 0;
        }
        
        $pdo = DbService::getInstance();
        foreach($data as $field => $value){
            $sqlFields[$field] = '`'.$field.'`';
            $sqlMarks[$field] = ':'.$field;
        }
        
        $sql = "insert into `".$this->tableName."`(".implode(',', $sqlFields).") values(".implode(',', $sqlMarks).")";
        $pdoStatement = $pdo->prepare($sql);
        foreach($sqlMarks as $filed => $fieldMark){
            if(is_array($data[$filed]) && count($data[$filed]) > 1){
                $pdoStatement->bindValue($fieldMark, $data[$filed][0], $data[$filed][1]);
            }else{
                $pdoStatement->bindValue($fieldMark, $data[$filed]);
            }
        }
        if(!$pdoStatement->execute()){
            $message = DbService::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return $pdo->lastInsertId();
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
        $pdo = null; // pdo对象
        $pdoStatement = null;
        $whereValueMark = ''; // 条件值的标识
        $whereValueValue = ''; // 条件值的值
        $message = ''; // 错误描述
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return false;
        }
        $pdo = DbService::getInstance();
        
        $sql = "delete from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        
        $pdoStatement = $pdo->prepare($sql);
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
            $message = DbService::getStatementError($pdoStatement);
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
        $pdo = DbService::getInstance();
        
        $sql = "update `".$this->tableName."` set ";
        foreach($datas as $dataField => $dataValue){
            $sqlFields[] = "`".$dataField."` = :".$dataField."";
        }
        $sql .= implode(', ', $sqlFields);
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        
        $pdoStatement = $pdo->prepare($sql);
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
            $message = DbService::getStatementError($pdoStatement);
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
        
        $pdo = DbService::getInstance();
        
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
        
        $pdoStatement = $pdo->prepare($sql);
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
            $message = DbService::getStatementError($pdoStatement);
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
        $pdo = DbService::getInstance();
        
        $sql = "select $field from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        $sql .= ' limit 0,1';
        
        $pdoStatement = $pdo->prepare($sql);
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
            $message = DbService::getStatementError($pdoStatement);
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
        $pdo = DbService::getInstance();
        
        $sql = "select ".$field." from `".$this->tableName."`";
        if(!empty($where['mark'])){
            $sql .= ' where '.$where['mark'];
        }
        $sql .= ' limit 0,1';
        $pdoStatement = $pdo->prepare($sql);
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
            $message = DbService::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $content = $pdoStatement->fetchColumn();
        
        return $content;
    }
}