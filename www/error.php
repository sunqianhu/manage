<?php
/**
* 错误捕获
*/

/**
 * 自定义错误处理
 * @param Exception $level 错误号
 * @param Exception $message 错误描述
 * @param Exception $file 错误文件
 * @param Exception $line 错误行
 * @param Exception $context 错误上下文
 */
function sunError($code, $message, $file, $line, $context){
    $errors = array(
        'type'=>'错误',
        'code'=>$code,
        'message'=>$message,
        'file'=>$file,
        'line'=>$line
        //'context'=>$context
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
        'message'=>$e->getMessage(),
        'file'=>$e->getFile(),
        'line'=>$e->getLine()
    );
    echo sunEchoFormat($errors);
}

/**
 * 错误输出
 * @param Exception $e 异常对象
 */
function sunEchoFormat($errors){
    $response = 'html';
    $echo = ''; // 输出
    
    if(!empty($_GET['response'])){
        $response = $_GET['response'];
    }
    
    if($response == 'json'){
        $echo = implode('，', $errors);
        $returnJsons = array(
            'status'=>'error',
            'msg'=>$echo
        );
        echo json_encode($returnJsons);
        exit;
    }
    
    $echo = implode('<br/>', $errors);
    echo $echo;
    exit;
}

set_error_handler("sunError");
set_exception_handler("sunException");
?>