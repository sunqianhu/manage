<?php
/**
 * 错误
 */
namespace app\controller;

use \app\service\SafeService;

class ErrorController extends BaseController{
    /**
     * 首页
     */
    function index(){
        $message = '';
        if(isset($_GET['message'])){
            $message = $_GET['message'];
        }
        $message = safeService::entity($message);
        
        $this->assign('message', $message);
        $this->display('error.php');
    }
}