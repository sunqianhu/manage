<?php
/**
 * 主框架
 */
namespace library\service;

use library\service\ConfigService;
use library\service\AuthService;
use library\service\TreeService;


class FrameMainService{
    /**
     * 得到页面左边菜单节点
     * @param string $active 活跃项
     * @access public
     * @return string 节点
     */
    static function getPageLeftMenu($active = ''){
        $menus = $_SESSION['menu'];
        $menus = TreeService::getDataTree($menus, 'child', 'id', 'parent_id');
        $menus = TreeService::addLevel($menus, 1);
        $node = '';
        
        $node .= '<ul>';
        $node .= self::getPageLeftMenuTree($menus, $active);
        $node .= '</ul>';
        
        return $node;
    }
    
    /**
     * 得到页面左边菜单树
     * @param array $menus 数据
     * @param string $active 活跃项
     * @return string
     */
    static function getPageLeftMenuTree($menus, $active){
        $node = '';
        $url = '';
        $liActive = '';
        $appDomain = ConfigService::getOne('app_domain');
        
        if(!empty($menus)){
            foreach($menus as $menu){
                if(!in_array($menu['type'], array(1,2))){
                    continue;
                }
                
                $url = $menu['url'];
                if(!empty($url)){
                    if(strpos($url, 'http') === false){
                        $url = $appDomain.$url;
                    }
                }else{
                    $url = 'javascript:;';
                }
                $liActive = '';
                if($menu['tag'] == $active){
                    $liActive = ' class="active"';
                }
            
                $node .= '
<li'.$liActive.'>
<a href="'.$url.'">';
                if($menu['icon_class']){
                    $node .= '
<span class="'.$menu['icon_class'].' icon"></span>';
                }
                $node .= '
<span class="text">'.$menu['name'].'</span>';
                if(!empty($menu['child'])){
                    $node .= '
<span class="iconfont icon-arrow_left arrow"></span>';
                }
                $node .= '
</a>';
                if(!empty($menu['child'])){
                    $node .= '
<ul>';
                    $node .= self::getPageLeftMenuTree($menu['child'], $active);
                    $node .= '
</ul>';
                }
            }
        }
        
        return $node;
    }
}