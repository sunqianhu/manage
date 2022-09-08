<?php
/**
* 站点配置
*/

return array(
    'app_name'=>'sun管理系统框架', // 站点名称
    'app_domain'=>'http://manage.sunqianhu123.cc/', // 站点访问域名
    
    // 缓存
    'cache_dir'=>dirname(__DIR__).'/cache/', // 缓存目录
    
    // 用户文件
    'user_file_path'=>__DIR__.'/userfile/', // 路径
    'user_file_domain'=>'http://manage.sunqianhu123.cc/userfile/', // 访问前缀
    
    // 数据库
    'db_type'=>'mysql',
    'db_host'=>'127.0.0.1',
    'db_port'=>'3306',
    'db_username'=>'root',
    'db_password'=>'sqh_mysql',
    'db_database'=>'manage',
    'db_charset'=>'utf8mb4'
);
?>