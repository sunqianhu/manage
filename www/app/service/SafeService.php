<?php
/**
 * 安全
 */
namespace app\service;

class SafeService{
    
    /**
     * 实体化
     * @param $datas 数据
     * @param $outKey 排除字段
     * @return 实体化后的数据
     */
    static function entity($datas, $outKey = ''){
        $outKeys = array();

        if(empty($datas)){
            return $datas;
        }

        if(is_array($datas)){
            foreach ($datas as $key => $data){
                if(!empty($outKey)){
                    $outKeys = explode(',', $outKey);
                    if(in_array($key, $outKeys, true)){
                        continue;
                    }
                }

                $datas[$key] = self::entity($data, $outKey);
            }
        }else{
            $datas = htmlspecialchars($datas);
        }

        return $datas;
    }
}