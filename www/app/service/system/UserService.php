<?php
/**
 * 用户服务
 */
namespace app\service\system;

use \app\model\system\UserMode;
use \app\model\system\MenuMode;

class userService{
    
    /**
     * 得到用户的菜单url
     * @access public
     * @return boolean
     */
    static function getMenuUrls($userId){
        $userMode = new UserMode();
        $menuMode = new MenuMode();
        $roleIdString = '';
        $menus = array();
        $urlRecords = array();
        $urlRecord = '';
        $urlLines = array();
        $urlLine = '';
        $urls = array();
        
        $roleIdString = $userMode->selectOne('role_id_string', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$userId
            )
        ));
        if(empty($roleIdString)){
            return $urls;
        }
        
        $menus = $menuMode->select('url', array(
            'mark'=>'id in (select menu_id from role_menu where role_id in (:role_id))',
            'value'=>array(
                ':role_id'=>$roleIdString
            )
        ));
        if(empty($menus)){
            return $urls;
        }
        
        $urlRecords = array_column($menus, 'url');
        foreach($urlRecords as $urlRecord){
            $urlLines = explode("\r\n", $urlRecord);
            foreach($urlLines as $urlLine){
                if(empty($urlLine)){
                    continue;
                }
                $urls[] = $urlLine;
            }
        }
        
        return $urls;
    }
    
    
}
