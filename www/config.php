<?php
/**
* 站点配置
*/

return array(
    'app_name'=>'sun管理系统框架', // 站点名称
    'app_domain'=>'http://manage.sunqianhu123.cc/', // 站点访问域名
    'view_path'=>__DIR__.'/view/', // 视图根目录
    
    // 数据库
    'db'=>array(
        'type'=>'mysql',
        'host'=>'127.0.0.1',
        'port'=>'3306',
        'database'=>'manage',
        'username'=>'root',
        'password'=>'sqh_mysql',
        'charset'=>'utf8mb4'
    )
);
?>