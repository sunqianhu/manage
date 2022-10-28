<?php
/**
 * ip
 */
namespace library;

class Ip{
    /**
     * 得到访问者的ip
     * @return string ip
     */
    function get(){
        $ip = '';
        
        if(getenv('HTTP_CLIENT_IP')){
            $ip = getenv('HTTP_CLIENT_IP');
        }else if(getenv('HTTP_X_FORWARDED_FOR')){
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }else if(getenv('REMOTE_ADDR')){
            $ip = getenv('REMOTE_ADDR');
        }else if(isset($_SERVER['REMOTE_ADDR'])){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }
}
