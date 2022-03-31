<?php
/**
* 站点配置
*/

return array(
    'site_name'=>'管理系统', // 站点名称
    'site_domain'=>'http://manage.sunqianhu123.cc/', // 站点访问域名
    'site_path'=>__DIR__.'/', // 站点根目录
    'view_path'=>__DIR__.'/view/', // 视图根目录
    
    // 数据库
    'db'=>array(
        'type'=>'mysql',
        'host'=>'127.0.0.1',
        'port'=>'3306',
        'database'=>'yydizi',
        'username'=>'yydizi',
        'password'=>'YawQD0ksYlY8s9AS',
        'charset'=>'utf8mb4'
    )
);
?>