<?php
/**
 * 基类模型
 */
namespace app\model;

use app\service\DbService;

class BaseModel{

    /**
     * 插入
     * @access public
     * @param array $data 记录
     * @return id 新插入记录id
     * @throws Exception
     */
    function insert($data){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $fieldNames = array(); // 字段名
        $fieldMarks = array(); // 字段标识
        $values = array(); // 值
        $message = '';
        
        if(empty($data)){
            return 0;
        }
        if(!isset($this->tableName) || $this->tableName == ''){
            return 0;
        }
        
        $pdo = DbService::getInstance();
        foreach($data as $field => $value){
            $fieldNames[] = '`'.$field.'`';
            $fieldMarks[] = ':'.$field;
            $values[] = $value;
        }
        
        $sql = "insert into `".$this->tableName."`(".implode(',', $fieldNames).") values(".implode(',', $fieldMarks).")";
        $pdoStatement = $pdo->prepare($sql);
        foreach($fieldMarks as $key => $fieldMark){
            $pdoStatement->bindValue($fieldMark, $values[$key]);
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
     * @param array $wheres 条件 mark value
     * @return boolean
     */
    function delete($wheres = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $message = '';
        $where = array(); // 一个条件
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return false;
        }
        $pdo = DbService::getInstance();
        
        $sql = "delete from `".$this->tableName."`";
        if(!empty($wheres)){
            $sql .= ' where '.implode(' and ', array_column($wheres, 'mark'));
        }
        
        $pdoStatement = $pdo->prepare($sql);
        if(!empty($wheres)){
            foreach($wheres as $where){
                $pdoStatement->bindValue(':'.$where['field'], $where['value']);
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
     * @param string $fields 字段
     * @param array $wheres 条件 mark value
     * @return string
     */
    function update($datas, $wheres = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        
        $field = '';
        $value = '';
        $sqlFields = array(); // sql的字段
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return false;
        }
        if(empty($datas)){
            return false;
        }
        $pdo = DbService::getInstance();
        
        $sql = "update `".$this->tableName."` set ";
        foreach($datas as $field => $value){
            $sqlFields[] = "`".$field."` = :".$field."";
        }
        $sql .= implode(', ', $sqlFields);
        if(!empty($wheres)){
            $sql .= ' where '.implode(' and ', array_column($wheres, 'mark'));
        }
        
        $pdoStatement = $pdo->prepare($sql);
        foreach($datas as $field => $value){
            $pdoStatement->bindValue(':'.$field, $value);
        }
        if(!empty($wheres)){
            foreach($wheres as $where){
                $pdoStatement->bindValue(':'.$where['field'], $where['value']);
            }
        }
        if(!$pdoStatement->execute()){
            $message = DbService::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        return true;
    }
    
    /**
     * 得到一个字段内容
     * @access public
     * @param string $field 字段
     * @param array $wheres 条件 mark value
     * @return string
     */
    function getOne($field, $wheres = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $message = ''; // 错误描述
        $content = ''; // 查询到的数据
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return $content;
        }
        $pdo = DbService::getInstance();
        
        $sql = "select $field from `".$this->tableName."`";
        if(!empty($wheres)){
            $sql .= ' where '.implode(' and ', array_column($wheres, 'mark'));
        }
        $sql .= ' limit 0,1';
        $pdoStatement = $pdo->prepare($sql);
        if(!empty($wheres)){
            foreach($wheres as $where){
                $pdoStatement->bindValue(':'.$where['field'], $where['value']);
            }
        }
        if(!$pdoStatement->execute()){
            $message = DbService::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $content = DbService::getOne($pdoStatement);
        
        return $content;
    }
    
    /**
     * 得到一条记录
     * @access public
     * @param string $field 字段
     * @param array $wheres 条件 mark value
     * @return array
     */
    function getRow($field, $wheres = array()){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $message = ''; // 错误描述
        $data = array(); // 查询到的数据
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return $data;
        }
        $pdo = DbService::getInstance();
        
        $sql = "select $field from `".$this->tableName."`";
        if(!empty($wheres)){
            $sql .= ' where '.implode(' and ', array_column($wheres, 'mark'));
        }
        $sql .= ' limit 0,1';
        
        $pdoStatement = $pdo->prepare($sql);
        if(!empty($wheres)){
            foreach($wheres as $where){
                $pdoStatement->bindValue(':'.$where['field'], $where['value']);
            }
        }
        if(!$pdoStatement->execute()){
            $message = DbService::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $data = DbService::getRow($pdoStatement);
        
        return $data;
    }
    
    /**
     * 得到查询条件的全部数据
     * @access public
     * @param string $field 字段
     * @param array $wheres 条件 mark value
     * @param string $order 排序
     * @param string $limit 限制
     * @return array
     */
    function getAll($field, $wheres = array(), $order = '', $limit = ''){
        $sql = '';
        $pdo = null;
        $pdoStatement = null;
        $message = ''; // 错误描述
        $datas = array(); // 查询到的数据
        
        if(!isset($this->tableName) || $this->tableName == ''){
            return $datas;
        }
        $pdo = DbService::getInstance();
        
        $sql = "select $field from `".$this->tableName."`";
        if(!empty($wheres)){
            $sql .= ' where '.implode(' and ', array_column($wheres, 'mark'));
        }
        if($order != ''){
            $sql .= ' '.$order;
        }
        if($limit != ''){
            $sql .= ' '.$limit;
        }
        $pdoStatement = $pdo->prepare($sql);
        if(!empty($wheres)){
            foreach($wheres as $where){
                $pdoStatement->bindValue(':'.$where['field'], $where['value']);
            }
        }
        if(!$pdoStatement->execute()){
            $message = DbService::getStatementError($pdoStatement);
            throw new \Exception($message);
        }
        
        $datas = DbService::getAll($pdoStatement);
        
        return $datas;
    }
}