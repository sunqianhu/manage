<?php
/**
 * 部门模型
 */
namespace app\model\system;

use app\service\DbService;
use app\model\BaseModel;

class departmentModel extends BaseModel{

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
        $errorMessage = '';
        $field = '';
        $value = '';
        $fieldSelects = array(); // 字段查询
        $fieldMarks = array(); // 字段查询
        $values = array(); // 值
        
        if(empty($data)){
            return 0;
        }
        
        $pdo = DbService::getInstance();
        foreach($data as $field => $value){
            $fieldSelects[] = '`'.$field.'`';
            $fieldMarks[] = ':'.$field;
            $values[] = $value;
        }
        
        $sql = "insert into department(".implode(',', $fieldSelects).") values(".implode(',', $fieldMarks).")";
        $pdoStatement = $pdo->prepare($sql);
        foreach($fieldMarks as $key => $fieldMark){
            $pdoStatement->bindValue($fieldMark, $values[$key]);
        }
        if(!$pdoStatement->execute()){
            $errorMessage = DbService::getStatementError($pdoStatement);
            throw new \Exception($errorMessage);
        }
        
        return $pdo->lastInsertId();
    }
    
}