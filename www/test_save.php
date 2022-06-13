<?php
/**
 * 保存
 */
require_once 'library/session.php';
require_once 'library/app.php';

use library\service\UserFileUploadService;

if(!UserFileUploadService::upload('file')){
    echo FileUploadService::$message;
}

echo UserFileUploadService::$path."\r\n";
echo UserFileUploadService::$name."\r\n";
echo UserFileUploadService::$extension."\r\n";
echo UserFileUploadService::$size."\r\n";
echo UserFileUploadService::$url."\r\n";
echo UserFileUploadService::$message;
