<?php
/**
 * 权限服务
 */
namespace library\service\system;

class PermissionService{
    
    /**
     * 得到首页节点树
     * @param array $permissions 数据
     * @return array
     */
    static function getIndexTreeNode($permissions, $level = 1){
        $node = '';

        if(!empty($permissions)){
            foreach($permissions as $permission){
                $node .= '
<tr tree_table_id="'.$permission['id'].'" tree_table_parent_id="'.$permission['parent_id'].'" tree_table_level="'.$level.'" class="tr tr'.$permission['id'].'">
<td>'.$permission['id'].'</td>
<td class="column">'.$permission['name'].'</td>
<td>'.$permission['tag'].'</td>
<td>'.$permission['sort'].'</td>';
                $node .= '
<td>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small sun-mr5" onclick="index.add('.$permission['id'].');" title="添加子权限">添加</a>
<a href="javascript:;" class="sun-button sun-button-small sun-button-secondary sun-mr5" onclick="index.edit('.$permission['id'].');">修改</a>
<span class="sun-dropdown-menu sun-dropdown-menu-align-right operation_more">
<div class="title"><a href="javascript:;" class="sun-button sun-button-secondary sun-button-small">更多 <span class="iconfont icon-arrow-down arrow"></span></a></div>
<div class="content">
<ul>
<li><a href="javascript:;" onClick="index.delete('.$permission['id'].')">删除</a></li>
</ul>
</div>
</span>
</td>
</tr>';
                if(!empty($permission['child'])){
                    $node .= self::getIndexTreeNode($permission['child'], ($level + 1));
                }
            }
        }else{
            $node = '<tr><td colspan="5" align="center">无权限</td></td>';
        }
        return $node;
    }
    
    
}
