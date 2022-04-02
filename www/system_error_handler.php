<?php
/**
* 系统错误/异常处理
*/

/**
 * 自定义错误处理
 * @param Exception $code 错误级别
 * @param Exception $message 错误描述
 * @param Exception $file 错误文件
 * @param Exception $line 错误行
 * @param Exception $context 错误上下文
 */
function sunError($code, $message, $file, $line){
    $errors = array(
        'type'=>'错误',
        'code'=>$code,
        'message'=>$message,
        'file'=>$file,
        'line'=>$line
    );
    echo sunEchoFormat($errors);
    exit;
}

/**
 * 自定义异常处理
 * @param Exception $e 异常对象
 */
function sunException($e){
    $errors = array(
        'type'=>'异常',
        'code'=>$e->getCode(),
        'message'=>$e->getMessage(),
        'file'=>$e->getFile(),
        'line'=>$e->getLine(),
        'trace'=>$e->getTraceAsString()
    );
    echo sunEchoFormat($errors);
}

/**
 * 错误输出
 * @param Exception $e 异常对象
 */
function sunEchoFormat($errors){
    $response = 'html'; // 响应类型
    $message = ''; // 错误消息
    $jsonEchos = array(); // json输出
    $view = null; // 视图对象
    
    
    if(!empty($_GET['response'])){
        $response = $_GET['response'];
    }
    
    // json
    if($response == 'json'){
        $message = implode('，', $errors);
        $jsonEchos = array(
            'status'=>'error',
            'msg'=>$message
        );
        return json_encode($jsonEchos);
    }
    
    // html
    return implode('<br/>', $errors);
}

set_error_handler("sunError", E_ALL);
set_exception_handler("sunException");
?>