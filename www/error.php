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
function sunError($level, $message, $file, $line, $context){
    echo '<div><b>错误</b><br/>错误号：'.$level.'<br/>错误描述：'.$message.'<br/>错误文件：'.$file.'<br/>错误行：'.$line.'</div>';
    exit;
}

/**
 * 自定义异常处理
 * @param Exception $e 异常对象
 */
function sunException($e){
    $line = $e->getLine();
    $message = $e->getMessage();
    $file = $e->getFile();
    
    echo '<div><b>异常</b><br/>异常描述：'.$message.'<br/>异常文件：'.$file.'<br/>异常行：'.$line.'</div>';
}

set_error_handler("sunError");
set_exception_handler("sunException");
?>