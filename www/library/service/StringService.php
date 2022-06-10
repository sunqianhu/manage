<?php
/**
 * 字符串服务
 */
namespace library\service;

class StringService{
    /**
     * 获取字符串长度
     * @param string $string 字符串
     * @return int 字符串长度
     */
    static function length($string){
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
     * 截取
     * @param string $string 字符串
     * @param int $start 开始位置
     * @param int $end 结束位置
     * @return string 截取后的字符串
     */
    static function sub($string, $start, $end){
        $stringNew = '';
        $matchs = array();
        $length = 0;
        $ellipsis = '...';
        
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
}
