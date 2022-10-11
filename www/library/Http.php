<?php
/**
 * http
 */
namespace library;

class Http{
    public $error = ''; // 错误描述
    public $result = ''; // 请求结果
    
    /**
     * 得到错误
     * @return String 错误描述
     */
    function getError(){
        return $this->error;
    }
    
    /**
     * 设置错误
     * @param String $error 错误描述
     * @return Boolean
     */
    function setError($error){
        return $this->error = $error;
    }
    
    /**
     * 得到结果
     * @return String 结果
     */
    function getResult(){
        return $this->result;
    }
    
    /**
     * 设置结果
     * @param String $result 结果
     * @return Boolean
     */
    function setResult($result){
        return $this->result = $result;
    }

    /**
     * get请求
     * @param String $url 网址
     * @param Array $options 选项参数
     * @return Boolean
     */
    function get($url, $options = array()){
        $ch = null;
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => false, // 禁止 cURL 验证对等证书
            CURLOPT_SSL_VERIFYHOST => 0 // 不检查服务器SSL证书中是否存在一个公用名
        );
        $result = '';

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $result = curl_exec($ch);
        
        if($result === false){
            $this->setError('错误号：'.curl_errno($ch).','.'错误描述：'.curl_error($ch));
            return false;
        }
        curl_close($ch);
        
        $this->setResult($result);
        
        return true;
    }
    
    /**
     * post请求
     * @param String $url 网址
     * @param Array $content 请求内容
     * @param Array $options 选项参数
     * @return Boolean
     */
    function post($url, $content = array(), $options = array()){
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
        $result = '';

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $result = curl_exec($ch);
        
        if($result === false){
            $this->setError('错误号：'.curl_errno($ch).','.'错误描述：'.curl_error($ch));
            return false;
        }
        curl_close($ch);
        
        $this->setResult($result);
        
        return true;
    }
}
