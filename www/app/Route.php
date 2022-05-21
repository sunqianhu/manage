<?php
/**
 * 路由
 */
namespace app;

class Route{
    /**
     * 得到路径
     */
    static function getPath(){
        $uri = '';
        $uris = array();
        $path = '';
        
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            $uri = $_SERVER['REQUEST_URI'];
            $uris = explode('?', $uri);
            $path = $uris[0];
        }
        
        return $path;
    }
    
    /**
     * 运行找路
     * @access public
     */
    function run(){
        $obj = null; // 控制器对象
        $uri = ''; // url
        $uris = array(); // url数组
        $parameter = ''; // 页面参数
        $parameters = array(); // 页面参数数组
        $path = ''; // 路径
        $paths = array(); // 路径数组
        $dir = ''; // 目录正斜杠
        $dirs = array(); // 目录数组
        $controller = 'IndexController'; // 控制器
        $action = 'index'; // 方法
        $actions = array(); // 方法数组
        $namespaceControllerPrefix = '\\app\\controller\\'; // 控制器命名空间前缀
        $class = ''; // 控制器class全路径
        
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            $uri = $_SERVER['REQUEST_URI'];
            $uris = explode('?', $uri);
            $path = $uris[0];
            if(isset($uris[1])){
                $parameter = $uris[1];
            }
            
            $path = trim($path, '/');
            $paths = explode('/', $path);
            $file = array_pop($paths);
            
            // 目录
            if(!empty($paths)){
                $dirs = $paths;
                $dir = implode('\\', $dirs).'\\';
            }
            
            // 控制器
            $file = str_replace('.html', '', $file);
            $file = str_replace('.json', '', $file);
            $files = explode('-', $file);
            
            $controller = $files[0];
            $controllers = explode('_', $controller);
            $controllers = array_map('ucfirst', $controllers);
            $controller = implode('', $controllers);
            $controller = $controller.'Controller';
            
            // 方法
            if(isset($files[1])){
                $action = $files[1];
                $actions = explode('_', $action);
                $actions = array_map('ucfirst', $actions);
                $action = implode('', $actions);
            }
            
            // get参数
            if($parameter !== ''){
                parse_str($parameter, $parameters);
                if(count($parameters) > 1){
                    $_GET = array_merge($_GET, $parameters);
                }
            }
        }
        
        $class = $namespaceControllerPrefix.$dir.$controller;
        $obj = new $class();
        $obj->$action();
    }
}



