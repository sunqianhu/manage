<?php
/**
 * 请求响应服务
 */
namespace library;

class Http{
    public $error = ''; // 错误描述
    
    /**
     * 得到错误
     * @return String 错误描述
     */
    static function getError(){
        return self::$error;
    }
    
    /**
     * 设置错误
     * @param String $error 错误描述
     * @return Boolean
     */
    static function setError($error){
        return self::$error = $error;
    }

    /**
     * get请求
     * @param String $url 网址
     * @param Array $options 选项参数
     * @return String 响应内容
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
            self::setError('错误号：'.curl_errno($ch).','.'错误描述：'.curl_error($ch));
            return false;
        }
        curl_close($ch);
        
        return $return;
    
        
    }
    
    /**
     * post请求
     * @param String $url 网址
     * @param Array $content 请求内容
     * @param Array $options 选项参数
     * @return String 响应内容
     */
    static function post($url, $content = array(), $options = array()){
        $ch = null;
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content,
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
            self::setError('错误号：'.curl_errno($ch).','.'错误描述：'.curl_error($ch));
            return false;
        }
        curl_close($ch);
        
        return $return;
    }
    
}
