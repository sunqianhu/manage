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
<td class="column">'.$menu['name'].'</td>
<td>'.$menu['tag'].'</td>
<td>'.$menu['permission'].'</td>
<td>'.$menu['sort'].'</td>';
                $node .= '
<td>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small sun_mr5" onclick="index.add('.$menu['id'].');" title="添加子部门">添加</a>
<a href="javascript:;" class="sun_button sun_button_small sun_button_secondary sun_mr5" onclick="index.edit('.$menu['id'].');">修改</a>
<span class="sun_dropdown_menu sun_dropdown_menu_align_right operation_more">
<div class="title"><a href="javascript:;" class="sun_button sun_button_secondary sun_button_small">更多 <span class="iconfont icon-arrow_down arrow"></span></a></div>
<div class="content">
<ul>
<li><a href="javascript:;" onClick="index.delete('.$menu['id'].')">删除</a></li>
</ul>
</div>
</span>
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
