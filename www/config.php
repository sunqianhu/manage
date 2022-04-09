<?php
/**
* 站点配置
*/

return array(
    'site_name'=>'sun后台管理框架', // 站点名称
    'site_domain'=>'http://manage.sunqianhu123.cc/', // 站点访问域名
    'site_path'=>__DIR__.'/', // 站点根目录
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