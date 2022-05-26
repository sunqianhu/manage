<?php
/**
 * 菜单服务
 */
namespace app\service\system;

class MenuService{
    
    /**
     * 得到首页节点树
     * @param array $menus 数据
     * @return array
     */
    static function getIndexTreeNode($menus){
        $node = '';
        $indent = 0;
        $i = 0;
        $styleClass = '';
        
        if(!empty($menus)){
        foreach($menus as $menu){
            $indent = $menu['level'] - 1;
            if($menu['level'] == 1 && empty($menu['child'])){
                $styleClass .= ' parent_close'; // 一级无子项的关闭
            }
            if($menu['level'] >= 2 && !empty($menu['child'])){
                $styleClass .= ' parent_close'; // 二级关闭以上有子项的都关闭
            }
            if($menu['level'] > 2){
                $styleClass .= ' child_close'; // 二级以上不显示
            }

            $node .= '
<tr tree_table_id="'.$menu['id'].'" tree_table_parent_id="'.$menu['parent_id'].'" class="tr tr'.$menu['id'].''.$styleClass.'">
<td>'.$menu['id'].'</td>
<td class="name">';
            for($i = 0; $i < $indent; $i ++){
                $node .= '<span class="indent"></span>';
            }
            if(!empty($menu['child'])){
                $node .= '<span class="iconfont icon-arrow_down arrow" onclick="index.treeTableToggle(this)"></span>';
            }
            $node .= $menu['name'].'
</td>
<td>'.$menu['sort'].'</td>
<td>';
            if($menu['parent_id'] != 0){
                $node .= '<a href="javascript:;" class="sun_button sun_button_small sun_button_secondary sun_mr5" onclick="index.edit('.$menu['id'].');">修改</a> ';
                $node .= '<a href="javascript:;" class="sun_button sun_button_secondary sun_button_small sun_mr5" onclick="index.delete('.$menu['id'].');">删除</a>';
            }
            $node .= '</td>
</tr>
';
            if(!empty($menu['child'])){
                $node .= self::getIndexTreeNode($menu['child']);
            }
        }
        }else{
            $node = '<tr><td colspan="5" align="center">无菜单</td></td>';
        }
        return $node;
    }
    
    
}
