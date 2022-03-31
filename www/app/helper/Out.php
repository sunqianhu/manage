<?php
/**
 * 输出
 */
namespace app\helper;

class Out{
    /**
     * 输出json
     * @param array $datas 数组
     * @param json json字符串
     */
    static function json($datas){
        return json_encode($datas);
    }
    
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