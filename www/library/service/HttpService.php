<?php
/**
 * 请求响应服务
 */
namespace library\service;

class HttpService{
    static $errorCode = ''; // 错误代码
    static $errorMessage = ''; // 错误描述

    /**
     * get请求
     * @param string $url 网址
     * @param array $options 选项参数
     * @return string 响应内容
     */
    static function get($url, $options = array()){
        $ch = null;
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => false, // 禁止 cURL 验证对等证书
            CURLOPT_SSL_VERIFYHOST => 0 // 不检查服务器SSL证书中是否存在一个公用名
        );
        $return = '';

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $return = curl_exec($ch);
        
        if($return === false){
            throw new \Exception(curl_errno($ch).' '.curl_error($ch));
        }
        
        curl_close($ch);
        
        return $return;
    
        
    }
    
    /**
     * post请求
     * @param string $url 网址
     * @param array $post 请求内容
     * @param array $options 选项参数
     * @return string 响应内容
     */
    static function post($url, $post = array(), $options = array()){
        $ch = null;
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => false, // 禁止 cURL 验证对等证书
            CURLOPT_SSL_VERIFYHOST => 0 // 不检查服务器SSL证书中是否存在一个公用名
        );
        $return = '';

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $return = curl_exec($ch);
        
        if($return === false){
            throw new \Exception(curl_errno($ch).' '.curl_error($ch));
        }
        
        curl_close($ch);
        
        return $return;
    }
    
}
