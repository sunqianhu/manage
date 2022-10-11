<?php
/**
 * 二维数组
 */
namespace library;

class ArrayTwo{
    /**
     * 得到下拉选项
     * @param Array $datas 二维数组
     * @param Array $selectKeys 选中项的key
     * @param String $key option的key
     * @param String $value option的值
     * @return String option字符串
     */
    static function getOption($datas, $selectKeys = array(), $key = 'id', $value = 'name'){
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
    
    /**
     * 得到某列的字符串
     * @param Array $datas 二维数组
     * @param String $column 列
     * @param String $split 分隔符
     * @return String
     */
    static function getColumnString($datas, $column, $split = ','){
        $content = '';
        $data = array();
        
        if(empty($datas)){
            return $content;
        }
        
        $data = array_column($datas, $column);
        if(empty($data)){
            return $content;
        }
        
        $content = implode($split, $data);
        
        return $content;
    }
    
    /**
     * 某列时间戳转时间
     * @param Array $datas 二维数组
     * @param String $columnOld 列老
     * @param String $columnNew 列新
     * @param String $split 分隔符
     * @return String
     */
    static function columnTimestampToTime($datas, $columnOld, $columnNew, $format = 'Y-m-d H:i:s'){
        $data = array();
        
        if(empty($datas)){
            return $datas;
        }
        
        foreach($datas as $key => $data){
            if(!isset($data[$columnOld])){
                continue;
            }
            
            $datas[$key][$columnNew] = date($format, $data[$columnOld]);
        }
        
        return $datas;
    }
}
