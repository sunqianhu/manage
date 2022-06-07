<?php
/**
 * 数组服务
 */
namespace library\service;

class ArrayService{
    /**
     * 得到数据树
     * @param array $datas 二维数组
     * @param string $keyOld 转换前的数组key
     * @param string $keyNew 转换后的数组key
     * @return array
     */
    static function stringToArray($datas, $keyOld, $keyNew, $split = ','){
        if(!empty($datas)){
            foreach($datas as &$data){
                $data[$keyNew] = explode(',', $data[$keyOld]);
            }
        }
        return $datas;
    }
    
    /**
     * 得到下拉选项
     * @param array $datas 二维数组
     * @return string value
     */
    static function getSelectOption($datas, $selectKeys = array(), $key = 'id', $value = 'name'){
        $node = ''; // 节点

        if(empty($datas)){
            return $node;
        }
        
        foreach($datas as $data){
            $node .= '<option value="'.$data[$key].'"';
            if(in_array($data[$key], $selectKeys)){
                $node .= ' selected="selected"';
            }
            $node .= '>'.$data[$value].'</option>'."\r\n";
        }
        
        return $node;
    }
    
}
