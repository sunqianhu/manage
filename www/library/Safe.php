<?php
/**
 * 安全
 */
namespace library;

class Safe{
    
    /**
     * 转实体
     * @param Array $datas 数据
     * @param Array $excludeField 排除字段
     * @return String 处理后可以被前台显示的字符串
     */
    static function entity($datas, $excludeField = ''){
        $excludeFields = array();
    
        if(empty($datas)){
            return $datas;
        }

        if(is_array($datas)){
            foreach ($datas as $field => $data){
                if($excludeField !== ''){
                    $excludeFields = explode(',', $excludeField);
                    array_walk($excludeFields, 'trim');
                    if(in_array($field, $excludeFields)){
                        continue;
                    }
                }
                
                $datas[$field] = self::entity($data, $excludeField);
            }
        }else{
            $datas = htmlspecialchars($datas);
        }

        return $datas;
    }
}