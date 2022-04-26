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
        $pathNumber = 0; // 页面路径数量
        $parameters = array(); // 参数

        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            $uri = $_SERVER['REQUEST_URI'];
            $uris = explode('?', $uri);
            
            // 页面
            $path = trim($uris[0], '/');
            $paths = explode('/', $path);
            $pathNumber = count($paths);
            
            if($pathNumber < 2){
                throw \Exception('url参数错误');
            }
            
            // action
            if(isset($paths[$pathNumber - 1])){
                self::$action = $paths[$pathNumber - 1];
            }
            
            // 控制器类
            $paths[$pathNumber - 2] = ucfirst($paths[$pathNumber - 2]);
            array_splice($paths, -1);

            self::$controller = implode('\\', $paths);
            
            // get参数
            if(isset($uris[1])){
                parse_str($uris[1], $parameters);
                if(count($parameters) > 1){
                    $_GET = array_merge($_GET, $parameters);
                }
            }
        }else{
            self::$controller = 'index';
            self::$action = 'index';
        }
        self::$controller = self::$controller.'Controller';
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



