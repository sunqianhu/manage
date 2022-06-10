<?php
/**
 * 菜单服务
 */
namespace library\service\system;

class MenuService{
    
    /**
     * 得到首页节点树
     * @param array $menus 数据
     * @return array
     */
    static function getIndexTreeNode($menus, $level = 1){
        $node = '';

        if(!empty($menus)){
            foreach($menus as $menu){
                $node .= '
<tr tree_table_id="'.$menu['id'].'" tree_table_parent_id="'.$menu['parent_id'].'" tree_table_level="'.$level.'" class="tr tr'.$menu['id'].'">
<td>'.$menu['id'].'</td>
<td class="name">'.$menu['name'].'</td>
<td>'.$menu['tag'].'</td>
<td>'.$menu['permission'].'</td>
<td>'.$menu['sort'].'</td>';
                $node .= '
<td>
<a href="javascript:;" class="sun_button sun_button_small sun_button_secondary sun_mr5" onclick="index.edit('.$menu['id'].');">修改</a> 
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small sun_mr5" onclick="index.delete('.$menu['id'].');">删除</a>
</td>
</tr>';
                if(!empty($menu['child'])){
                    $node .= self::getIndexTreeNode($menu['child'], ($level + 1));
                }
            }
        }else{
            $node = '<tr><td colspan="5" align="center">无菜单</td></td>';
        }
        return $node;
    }
    
    
}
