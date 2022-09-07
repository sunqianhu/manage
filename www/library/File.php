<?php
/**
 * 文件服务
 */
namespace library;

class File{
    /**
     * 得到可读字节大小
     * @param int $byte 字节数
     * @return string 可读字节大小
     */
    static function getByteReadable($byte){
        $readable = '';
        
        if($byte < 1024){
            $readable = $byte.'B';
        }else if($byte < 1024 * 1024){
            $readable  = floor($byte / 1024).'KB';
        }else if($byte < 1024 * 1024 * 1024){
            $readable  = round($byte / 1024 / 1024, 1).'MB';
        }else if($byte < 1024 * 1024 * 1024 * 1024){
            $readable  = round($byte / 1024 / 1024 / 1024, 2).'GB';
        }
        
        return $readable;
    }
    
}
