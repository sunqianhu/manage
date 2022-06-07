<?php
/**
 * 安全
 */
namespace library\service;

class SafeService{
    
    /**
     * 前台显示
     * @param array $datas 数据
     * @param array $excludeField 排除字段
     * @return string 处理后可以被前台显示的字符串
     */
    static function frontDisplay($datas, $excludeFields = array()){
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

                $datas[$field] = self::frontDisplay($data, $excludeFields);
            }
        }else{
            $datas = htmlspecialchars($datas);
        }

        return $datas;
    }
}