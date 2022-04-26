<?php
/**
 * 基类模型
 */
namespace app\model;

use app\Config;
use app\service\DbHelperService;

class BaseModel extends \PDO{
    /**
     * 构造函数
     */
    function __construct(){
        $dsn = '';    
        $config = array();
        
        $config = Config::get('db');
        if(
            empty($config) || 
            empty($config['type']) ||
            empty($config['host']) ||
            empty($config['port']) ||
            empty($config['database']) ||
            empty($config['charset']) ||
            empty($config['username']) ||
            empty($config['password'])
        ){
            throw new \Exception('数据库配置错误');
        }
        
        $dsn = $config['type'].
        ':host='.$config['host'].
        ';port='.$config['port'].
        ';dbname='.$config['database'].
        ';charset='.$config['charset'];
        parent::__construct($dsn, $config['username'], $config['password']);
    }
}