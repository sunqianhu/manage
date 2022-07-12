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
        $tableName = $this->tableName;
        
        return DbService::insert($tableName, $data);
    }
    
    /**
     * 删除
     * @access public
     * @param array $where 条件 mark value
     * @return boolean
     * @throws Exception
     */
    function delete($where = array()){
        $tableName = $this->tableName;
        
        return DbService::delete($tableName, $where);
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
        $tableName = $this->tableName;
        
        return DbService::update($tableName, $datas, $where);
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
    function selectAll($field, $where = array(), $order = '', $limit = ''){
        $tableName = $this->tableName;
        
        return DbService::selectAll($tableName, $field, $where, $order, $limit);
    }
    
    /**
     * 得到一条记录
     * @access public
     * @param string $field 字段
     * @param array $where 条件 mark value
     * @return array
     */
    function selectRow($field, $where = array()){
        $tableName = $this->tableName;
        
        return DbService::selectRow($tableName, $field, $where);
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
        $tableName = $this->tableName;
        
        return DbService::selectOne($tableName, $field, $where);
    }
}