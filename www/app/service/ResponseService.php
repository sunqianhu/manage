<?php
/**
 * 响应服务
 */
namespace app\service;

class ResponseService{
    /**
     * 数据parent_id转换为pid
     * @param string $status 状态
     * @param string $msg 描述
     * @param array $data 数据
     * @return string
     */
    static function json($status, $message, $data = array()){
        $return = array(
            'status'=>$status,
            'message'=>$message,
            'data'=>$data
        );
        return json_encode($return);
    }
}
