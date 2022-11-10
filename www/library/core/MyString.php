<?php
/**
 * 字符串
 */
namespace library\core;

use library\core\Safe;

class MyString{
    /**
     * 得到字符串长度
     * @param string $string 字符串
     * @return int 字符串长度
     */
    static function getLength($string){
        $matchs = array();
        $length = 0;
        
        if($string === ''){
            return $length;
        }

        if(function_exists('mb_utf8length')){
            $length = mb_utf8length($string, 'utf-8');
        }else{
            preg_match_all('/./u', $string, $matchs);
            $length = count($matchs[0]);
        }
        
        return $length;
    }

    /**
     * 获取字符串的一部分（截取）
     * @param string $string 字符串
     * @param integer $start 开始位置
     * @param integer $end 结束位置
     * @return string 截取后的字符串
     */
    static function getSub($string, $start, $end){
        $stringNew = '';
        $matchs = array();
        $length = 0;
        
        if($string === ''){
            return $stringNew;
        }
        
        if(function_exists('mb_substr')){
            $stringNew = mb_substr($string, $start, $end, 'utf-8');
        }else{
            preg_match_all('/./u', $string, $matchs);
            $stringNew = join('', array_slice($matchs[0], $start, $end));
        }
        
        return $stringNew;
    }
    
    /**
     * 得到字符串的一部分显示
     * @param string $string 字符串
     * @param integer $length 截取长度
     * @return string 截取后的字符串
     */
    static function getSubFromZero($string, $length){
        $stringNew = '';
        $total = 0; // 字符串总长度
        
        if($string === ''){
            return $stringNew;
        }
        
        $total = self::getLength($string);
        if($total <= $length){
            return $string;
        }
        
        $stringNew = self::getSub($string, 0, $length).'...';
        return $stringNew;
    }
}
