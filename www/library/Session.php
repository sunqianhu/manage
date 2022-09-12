<?php
/**
 * 会话
 */
namespace library;

class Session{
    /**
     * 开启session
     */
    static function start(){
        session_start();
    }
    
}