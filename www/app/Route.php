<?php
/**
 * 路由
 */
namespace app;

class Route{
    static public $controller = '';
    static public $action = '';
    
    /**
     * 构造
     */
    public function __construct(){
        $uri = ''; // uri
        $uris = array(); // uris
        $path = ''; // 页面路径
        $paths = array(); // 页面路径
        $parameters = array(); // 参数

        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            $uri = $_SERVER['REQUEST_URI'];
            $uris = explode('?', $uri);
            
            // 页面
            $path = trim($uris[0], '/');
            $paths = explode('/', $path);
            if(isset($paths[0])){
                self::$controller = $paths[0];
            }
            if(isset($paths[1])){
                self::$action = $paths[1];
            }else{
                self::$action = 'index';
            }
            
            // get参数
            if(isset($uris[1])){
                $parameters = parse_str($uris[1]);
                if(count($parameters) > 1){
                    $_GET = array_merge($_GET, $parameters);
                }
            }
        }else{
            self::$controller = 'index';
            self::$action = 'index';
        }
        
        self::$controller = str_replace('_', '\\', self::$controller);
        self::$controller = ucfirst(self::$controller);
    }
    
    /**
     * 运行找路
     * @access public
     */
    function run(){
        $obj = null;
        $class = 'app\\controller\\'.self::$controller;
        $action = self::$action;
        
        $obj = new $class();
        $obj->$action();
    }
}



