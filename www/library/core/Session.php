<?php
/**
 * 会话
 */
namespace library\core;

class Session{
    /**
     * 开启session
     */
    function start(){
        session_start();
    }
    
}