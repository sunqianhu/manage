<?php
/**
 * 安全
 */
namespace app\service;

class SafeService{
    
    /**
     * 实体化
     * @param $datas 数据
     * @param $excludeField 排除字段
     * @return 实体化后的数据
     */
    static function entity($datas, $excludeFields = array()){
        if(empty($datas)){
            return $datas;
        }

        if(is_array($datas)){
            foreach ($datas as $field => $data){
                if(!empty($excludeField)){
                    if(in_array($field, $excludeFields, true)){
                        continue;
                    }
                }

                $datas[$field] = self::entity($data, $excludeFields);
            }
        }else{
            $datas = htmlspecialchars($datas);
        }

        return $datas;
    }
}