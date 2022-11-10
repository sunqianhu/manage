<?php
/**
 * 二维数组
 */
namespace library\core;

class ArrayTwo{
    /**
     * 得到下拉选项
     * @param array $datas 二维数组
     * @param array $selectKeys 选中项的key
     * @param string $key option的key
     * @param string $value option的值
     * @return string option字符串
     */
    static function getOption($datas, $selectKeys = array(), $key = 'id', $value = 'name'){
        $tag = ''; // 标签

        if(empty($datas)){
            return $tag;
        }
        
        foreach($datas as $data){
            $tag .= '<option value="'.$data[$key].'"';
            if(in_array($data[$key], $selectKeys)){
                $tag .= ' selected="selected"';
            }
            $tag .= '>'.$data[$value].'</option>'."\r\n";
        }
        
        return $tag;
    }
    
    /**
     * 得到某列的字符串
     * @param array $datas 二维数组
     * @param string $column 列
     * @param string $split 分隔符
     * @return string
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
     * @param array $datas 二维数组
     * @param string $columnOld 列老
     * @param string $columnNew 列新
     * @param string $split 分隔符
     * @return string
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
