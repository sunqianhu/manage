<?php
/**
 * 安全
 */
namespace library;

class Safe{
    
    /**
     * 前台显示
     * @param array $datas 数据
     * @param array $excludeField 排除字段
     * @return string 处理后可以被前台显示的字符串
     */
    static function frontDisplay($datas, $excludeField = ''){
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
                
                $datas[$field] = self::frontDisplay($data, $excludeField);
            }
        }else{
            $datas = htmlspecialchars($datas);
        }

        return $datas;
    }
}